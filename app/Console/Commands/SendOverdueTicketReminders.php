<?php

namespace App\Console\Commands;

use App\Mail\OverdueTicketReminder;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendOverdueTicketReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tickets:send-overdue-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a daily reminder email for every open ticket that has passed its action dateline';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $tickets = Ticket::where('status', 'Open')
            ->whereNotNull('dateline')
            ->with('dep_responsible.head_department', 'sub_dep_responsible.head_subdepartment', 'gm_responsible')
            ->get();

        $sent = 0;

        foreach ($tickets as $ticket) {
            $deadline = $ticket->dateline_extension ?? $ticket->dateline;

            if (Carbon::parse($deadline)->isFuture()) {
                continue;
            }

            if ($ticket->last_overdue_reminder_at && Carbon::parse($ticket->last_overdue_reminder_at)->isToday()) {
                continue;
            }

            $emails = collect([
                optional($ticket->gm_responsible)->email,
                optional(optional($ticket->dep_responsible)->head_department)->email,
                optional(optional($ticket->sub_dep_responsible)->head_subdepartment)->email,
            ])->filter()->unique();

            if ($emails->isEmpty()) {
                Log::warning("SendOverdueTicketReminders: no recipient email found for ticket #{$ticket->id}, skipping.");
                continue;
            }

            Mail::to($emails)->queue(new OverdueTicketReminder($ticket->id));

            $ticket->last_overdue_reminder_at = now();
            $ticket->save();

            $sent++;
        }

        Log::info("SendOverdueTicketReminders: {$sent} reminder(s) sent.");
        $this->info("{$sent} reminder(s) sent.");

        return 0;
    }
}
