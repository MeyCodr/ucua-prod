<?php

namespace App\Http\Controllers;

use App\Models\Approval;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\TicketContractor;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function ShowWelcome(Request $request)
    {
        return view('welcome');
    }

    public function ShowDashboard(Request $request)
    {
        $user_role = Auth::user()->groups->pluck('name');

        $approval = collect();
        if ($user_role->contains(function ($role) {
            return in_array($role, ['hodiv', 'hodept', 'hop', 'hos']);
        })) {
            $approval = Approval::where([
                ['approver_level', 1],
                ['approver_status', 'Pending']
            ])->pluck('ticket_id');
        } elseif ($user_role->contains(function ($role) {
            return in_array($role, ['admin', 'she_admin']);
        })) {
            $level_1 = Approval::where([
                ['approver_level', 1],
                ['approver_status', 'Verified']
            ])->pluck('ticket_id');

            $approval = Approval::whereIn('ticket_id', $level_1)
                ->where([
                    ['approver_level', 2],
                    ['approver_status', 'Pending']
                ])->pluck('ticket_id');
        } else {
            return redirect()->back()->withErrors(['error' => 'You do not have permission to view this page.']);
        }

        $numTicketsPendingVerify = Ticket::whereIn('id', $approval)->orderBy('created_at', 'desc')->count();
        // get number of pending verify tickets
        // $numTicketsPendingVerify = Approval::where('approver_status', 'Pending')->count();

        $mode = 0;
        $text = null;

        return view('dashboard', compact('numTicketsPendingVerify', 'mode', 'text'));
    }
}
