<?php

namespace App\Mail;

use App\Models\PointHistory;
use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PendingRedeemPoint extends Mailable
{
    use Queueable, SerializesModels;

    public $redeemId;
    public $submitter;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($redeemId, $staffId)
    {
        $this->redeemId = PointHistory::find($redeemId);
        $this->submitter = Ticket::where('staff_id', $staffId)->latest()->first();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
       $mail = $this->subject('New Point Redeem Request')
            ->markdown('Mail.PendingRedeemPoint');

        return $mail;
    }
}
