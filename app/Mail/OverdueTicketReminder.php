<?php

namespace App\Mail;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OverdueTicketReminder extends Mailable
{
    use Queueable, SerializesModels;

    public $ticket;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($ticketId)
    {
        $this->ticket = Ticket::with('plant', 'department', 'sub_department', 'dep_responsible.head_department', 'sub_dep_responsible.head_subdepartment', 'gm_responsible')->find($ticketId);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('[Overdue] Action Required - UCUA Observation #' . $this->ticket->id)
            ->markdown('Mail.OverdueTicketReminder');
    }
}
