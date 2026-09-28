<?php

namespace App\Mail;

use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * One email per head listing every overdue open ticket they are responsible for,
 * most overdue first, instead of one email per ticket.
 */
class OverdueTicketDigest extends Mailable
{
    use Queueable, SerializesModels;

    public $recipientName;

    /**
     * Plain arrays rather than models: queued mailables re-fetch models on unserialize,
     * which would drop the computed overdue values and the sort order.
     *
     * @var array<int, array{id:int, responsible:string, plant:string, dateline:string, days_overdue:int}>
     */
    public $rows;

    /**
     * @param  string|null  $recipientName
     * @param  array  $ticketIds
     * @return void
     */
    public function __construct($recipientName, array $ticketIds)
    {
        $this->recipientName = $recipientName;
        $this->rows = Ticket::with('plant_involve', 'dep_responsible', 'sub_dep_responsible')
            ->whereIn('id', $ticketIds)
            ->get()
            ->map(function ($ticket) {
                $deadline = Carbon::parse($ticket->dateline_extension ?? $ticket->dateline);
                return [
                    'id' => $ticket->id,
                    'responsible' => $ticket->sub_dep_responsible->name ?? $ticket->dep_responsible->name ?? $ticket->dept_res_other ?? '—',
                    'plant' => $ticket->plant_involve->name ?? '—',
                    'dateline' => $deadline->format('d/m/Y'),
                    'days_overdue' => $deadline->copy()->startOfDay()->diffInDays(today()),
                ];
            })
            ->sortByDesc('days_overdue')
            ->values()
            ->all();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $count = count($this->rows);

        return $this->subject('[Overdue] ' . $count . ' UCUA ' . ($count == 1 ? 'observation needs' : 'observations need') . ' your action')
            ->markdown('Mail.OverdueTicketDigest');
    }
}
