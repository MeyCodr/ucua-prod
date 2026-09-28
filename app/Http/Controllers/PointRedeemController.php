<?php

namespace App\Http\Controllers;

use App\Mail\ApprovedRedeemPoint;
use App\Mail\PendingRedeemPoint;
use App\Models\Group;
use App\Models\PointHistory;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PointRedeemController extends Controller
{
    //page for show point
    public function showRedeemPage(Request $request, $staff_id)
    {
        $point_total = PointHistory::where([['staff_id', $staff_id], ['action', 'New']])->sum('points');
        $point_floating = PointHistory::where([['staff_id', $staff_id], ['action', 'Redeem'],['approver_id', null], ['respond_at', null]])->sum('points');
        $point_redeem = PointHistory::where([['staff_id', $staff_id], ['action', 'Redeem'],['approver_id', '!=', null], ['respond_at', '!=', null]])->sum('points');
        $point_balance = $point_total - $point_floating - $point_redeem;

        $tabs = collect([
            (object) ['id' => 1, 'name' => 'Pending', 'link' => route('redeem.point', ['staff_id' => $staff_id, 'status' => 'Pending']), 'isActive' => $request->status == 'Pending'],
            (object) ['id' => 2, 'name' => 'Approved', 'link' => route('redeem.point', ['staff_id' => $staff_id, 'status' => 'Approved']), 'isActive' => $request->status == 'Approved'],
        ]);

        if ($request->status == 'Pending') {
            $query = PointHistory::where([['staff_id', $staff_id], ['action', 'Redeem'], ['approver_id', null], ['respond_at', null]]);
        } else {
            $query = PointHistory::where([['staff_id', $staff_id], ['action', 'Redeem'], ['approver_id', '!=', null], ['respond_at', '!=', null]]);
        }

        // Sortable column headers. Newest request first by default.
        $sortable = ['points' => 'points', 'created_at' => 'created_at', 'respond_at' => 'respond_at'];
        $sortField = $request->input('sort', 'created_at');
        $sortField = array_key_exists($sortField, $sortable) ? $sortField : 'created_at';
        $sortDirection = $request->input('direction') === 'asc' ? 'asc' : 'desc';

        // `points` is stored as text, so sort it as a number (otherwise 100 would come before 50).
        $orderColumn = $sortField === 'points' ? DB::raw('CAST(points AS UNSIGNED)') : $sortable[$sortField];

        $redeem = $query->orderBy($orderColumn, $sortDirection)
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        $submitter = Ticket::where('staff_id', $staff_id)->latest()->first();

        return view('submitter.redeem_point', [
            'staff_id' => $staff_id,
            'point_total' => $point_total,
            'point_floating' => $point_floating,
            'point_redeem' => $point_redeem,
            'point_balance' => $point_balance,
            'tabs' => $tabs,
            'redeems' => $redeem,
            'submitter' => $submitter,
            'sortField' => $sortField,
            'sortDirection' => $sortDirection,
        ]);
    }

    //submit redeem request
    public function submitRedeemRequest(Request $request, $staff_id)
    {
        // The form only offers these amounts, but the browser check can be bypassed, so the
        // server enforces both the allowed amounts and the available balance.
        $request->validate(
            ['points' => ['required', 'integer', Rule::in([10, 50, 100])]],
            ['points.in' => 'Please choose 10, 50 or 100 points.']
        );
        $points = (int) $request->points;

        // Check the balance and create the request in one transaction. Locking the staff's rows
        // stops two simultaneous requests from both passing the check.
        $redeem = DB::transaction(function () use ($staff_id, $points, &$balance) {
            $rows = PointHistory::where('staff_id', $staff_id)->lockForUpdate()->get();
            $redeemRows = $rows->where('action', 'Redeem');

            // Same rule as the Redeem page: earned - waiting for approval - already approved.
            $balance = $rows->where('action', 'New')->sum('points')
                - $redeemRows->whereNull('approver_id')->whereNull('respond_at')->sum('points')
                - $redeemRows->whereNotNull('approver_id')->whereNotNull('respond_at')->sum('points');

            if ($points > $balance) {
                return null;
            }

            $redeem = new PointHistory();
            $redeem->staff_id = $staff_id;
            $redeem->action = 'Redeem';
            $redeem->points = $points;
            $redeem->save();

            return $redeem;
        });

        if (!$redeem) {
            return redirect()->route('redeem.point', ['staff_id' => $staff_id, 'status' => 'Pending'])
                ->withErrors(['points' => 'You cannot redeem ' . $points . ' points. You only have ' . max($balance, 0) . ' available point' . (max($balance, 0) == 1 ? '' : 's') . '.']);
        }

        $she_admin = Group::where('name', 'she_admin')->first()->users;

        $all_she_admin = [];

        foreach ($she_admin as $she_admins) {
            array_push($all_she_admin, $she_admins->email);
        }

        Mail::to($all_she_admin)
            ->queue(new PendingRedeemPoint($redeem->id, $staff_id));

        return redirect()->route('redeem.point', ['staff_id' => $staff_id, 'status' => 'Pending' ])->with('success', 'Redeem request submitted successfully.');
    }

    //show redeem list
    public function showRedeemList(Request $request, $status)
    {
        $tabs = collect([
            (object) ['id' => 1, 'name' => 'Pending', 'link' => route('admin.redeem.list', ['status' => 'Pending']), 'isActive' => $status == 'Pending'],
            (object) ['id' => 2, 'name' => 'Approved', 'link' => route('admin.redeem.list', ['status' => 'Approved']), 'isActive' => $status == 'Approved'],
        ]);

        if ($status == 'Pending') {
            $query = PointHistory::where([['action', 'Redeem'], ['approver_id', null], ['respond_at', null]]);
        } else {
            $query = PointHistory::where([['action', 'Redeem'], ['approver_id', '!=', null], ['respond_at', '!=', null]]);
        }

        $sortable = [
            'id' => 'id',
            'staff_id' => 'staff_id',
            'points' => 'points',
            'created_at' => 'created_at',
        ];

        $sortField = $request->input('sort', 'created_at');
        $sortField = array_key_exists($sortField, $sortable) ? $sortField : 'created_at';
        $sortDirection = $request->input('direction') === 'asc' ? 'asc' : 'desc';

        $redeem = $query->orderBy($sortable[$sortField], $sortDirection)
            ->paginate(10)
            ->withQueryString();

        $staffIds = $redeem->pluck('staff_id')->filter()->unique()->values();

        $namesByStaffId = Ticket::whereIn('staff_id', $staffIds)
            ->orderBy('created_at', 'desc')
            ->get(['staff_id', 'name'])
            ->groupBy('staff_id')
            ->map(fn ($tickets) => $tickets->first()->name);

        foreach ($redeem as $item) {
            $item->reporter_name = $namesByStaffId->get($item->staff_id);
        }

        return view('point.redeem_point_list', [
            'tabs' => $tabs,
            'redeems' => $redeem,
            'status' => $status,
            'sortField' => $sortField,
            'sortDirection' => $sortDirection,
        ]);
    }

    //show redeem detail
    public function showRedeemDetail($status, $staff_id, $redeem_id)
    {
        $redeem = PointHistory::where([['action', 'Redeem'], ['approver_id', null], ['respond_at', null]])->find($redeem_id);
        $submitter = Ticket::where('staff_id', $staff_id)->latest()->first();

        return view('point.redeem_point_detail', [
            'submitter' => $submitter,
            'redeem' => $redeem,
            'status' => $status,
        ]);
    }

    //approve redeem request
    public function approveRedeemRequest($status, $staff_id, $redeem_id)
    {
        $redeem = PointHistory::where([['action', 'Redeem'], ['staff_id', $staff_id], ['approver_id', null], ['respond_at', null]])->find($redeem_id);
        $redeem->approver_id = auth()->user()->id;
        $redeem->respond_at = now();
        $redeem->save();

        $submitter = Ticket::where('staff_id', $staff_id)->latest()->first();
        Mail::to($submitter->email)->cc('shephn@phn.com.my')
            ->queue(new ApprovedRedeemPoint($redeem_id, $staff_id));

        return redirect()->route('admin.redeem.list', ['status' => $status])->with('success', 'Redeem request approved successfully.');
    }
}
