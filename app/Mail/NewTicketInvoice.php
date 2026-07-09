<?php

namespace App\Mail;

use App\Models\Ticket;
use App\Models\Unsafe;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class NewTicketInvoice extends Mailable
{
    use Queueable, SerializesModels;

    public $ticket;
    public $unsafe_cond_act;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($ticketId, $unsafeId)
    {
        $this->ticket = Ticket::with('plant', 'plant_involve', 'department', 'dep_responsible', 'gm_responsible', 'stop_culture', 'zero_harm', 'rank', 'site')->find($ticketId);
        $this->unsafe_cond_act = Unsafe::find($unsafeId);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mail = $this->subject('New UCUA Observation')->markdown('Mail.NewTicketInvoice');

        return $mail;
    }
}
