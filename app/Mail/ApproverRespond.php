<?php

namespace App\Mail;

use App\Models\Approval;
use App\Models\Branch;
use App\Models\Location;
use App\Models\State;
use App\Models\Ticket;
use App\Models\Unsafe;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ApproverRespond extends Mailable
{
    use Queueable, SerializesModels;

    public $ticket;
    public $unsafe_cond_act;
    public $approval;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($ticketId, $unsafeId, $approvalId)
    {
        $this->ticket = Ticket::find($ticketId);
        $this->unsafe_cond_act = Unsafe::find($unsafeId);
        $this->approval = Approval::find($approvalId);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mail = $this->subject('UCUA Observation Status')->markdown('Mail.ApproverRespond');
        
        return $mail;
    }
}
