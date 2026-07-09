<?php

namespace App\Http\Controllers;

use App\Mail\ApprovedRedeemPoint;
use App\Mail\PendingRedeemPoint;
use App\Models\Group;
use App\Models\PointHistory;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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

        $redeem = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

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
        ]);
    }

    //submit redeem request
    public function submitRedeemRequest(Request $request, $staff_id)
    {
        //create redeem request
        $redeem = new PointHistory();
        $redeem->staff_id = $staff_id;
        $redeem->action = 'Redeem';
        $redeem->points = $request->points;
        $redeem->save();

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
    public function showRedeemList($status)
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

        $redeem = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('point.redeem_point_list', [
            'tabs' => $tabs,
            'redeems' => $redeem,
            'status' => $status,
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
