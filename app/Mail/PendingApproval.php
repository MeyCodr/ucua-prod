<?php

namespace App\Mail;

use App\Models\Ticket;
use App\Models\Unsafe;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class PendingApproval extends Mailable
{
    use Queueable, SerializesModels;

    public $ticket;
    public $unsafe_cond_act;
    public $level;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($ticketId, $unsafeId, $level)
    {
        $this->ticket = Ticket::with('plant', 'plant_involve', 'department', 'sub_department', 'dep_responsible', 'dep_responsible.head_department', 'sub_dep_responsible', 'sub_dep_responsible.head_subdepartment', 'gm_responsible', 'stop_culture', 'zero_harm', 'rank', 'site')->find($ticketId);
        $this->unsafe_cond_act = Unsafe::find($unsafeId);
        $this->level = $level;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mail = $this->subject('New UCUA Observation')
            ->markdown('Mail.PendingApproval');

        return $mail;
    }
}
