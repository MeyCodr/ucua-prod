<?php

namespace App\Mail;

use App\Models\PointHistory;
use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ApprovedRedeemPoint extends Mailable
{
    use Queueable, SerializesModels;

    public $redeem;
    public $submitter;
    public $point_total;
    public $point_floating;
    public $point_redeem;
    public $point_balance;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($redeem_id, $staff_id)
    {
        $this->redeem = PointHistory::find($redeem_id);
        $this->submitter = Ticket::where('staff_id', $staff_id)->latest()->first();
        $this->point_total = PointHistory::where([['staff_id', $staff_id], ['action', 'New']])->sum('points');
        $this->point_floating = PointHistory::where([['staff_id', $staff_id], ['action', 'Redeem'],['approver_id', null], ['respond_at', null]])->sum('points');
        $this->point_redeem = PointHistory::where([['staff_id', $staff_id], ['action', 'Redeem'],['approver_id', '!=', null], ['respond_at', '!=', null]])->sum('points');
        $this->point_balance = $this->point_total - $this->point_floating - $this->point_redeem;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mail = $this->subject('Point Redemption Approved!')->markdown('Mail.ApprovedPointRedeem');

        return $mail;
    }
}
