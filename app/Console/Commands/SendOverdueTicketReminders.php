<?php

namespace App\Console\Commands;

use App\Mail\OverdueTicketDigest;
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
    protected $signature = 'tickets:send-overdue-reminders
                            {--dry-run : List who would be emailed and for how many tickets, without sending anything}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send each responsible head one daily summary email of their open tickets that have passed the action dateline';

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
            ->with('dep_responsible.head_department', 'sub_dep_responsible.head_subdepartment')
            ->get()
            ->filter(function ($ticket) {
                $deadline = $ticket->dateline_extension ?? $ticket->dateline;

                // Already included in a summary today (e.g. the command was re-run).
                $remindedToday = $ticket->last_overdue_reminder_at && Carbon::parse($ticket->last_overdue_reminder_at)->isToday();

                return Carbon::parse($deadline)->isPast() && !$remindedToday;
            });

        // Group by recipient. Responsible GM is no longer captured on tickets; the head of the
        // responsible department (and sub-department, when set) owns the follow-up.
        $recipients = [];
        $skipped = 0;

        foreach ($tickets as $ticket) {
            $heads = collect([
                optional($ticket->dep_responsible)->head_department,
                optional($ticket->sub_dep_responsible)->head_subdepartment,
            ])->filter(function ($head) {
                return $head && $head->email;
            })->unique('email');

            if ($heads->isEmpty()) {
                $skipped++;
                Log::warning("SendOverdueTicketReminders: no recipient email found for ticket #{$ticket->id}, skipping.");
                continue;
            }

            foreach ($heads as $head) {
                $recipients[$head->email] = $recipients[$head->email] ?? ['name' => $head->name, 'ticket_ids' => []];
                $recipients[$head->email]['ticket_ids'][] = $ticket->id;
            }
        }

        if ($this->option('dry-run')) {
            $this->table(
                ['Recipient', 'Email', 'Overdue tickets'],
                collect($recipients)->map(function ($r, $email) {
                    return [$r['name'], $email, count($r['ticket_ids'])];
                })->sortByDesc(2)->values()->all()
            );
            $this->info(count($recipients) . " summary email(s) would be sent; {$skipped} ticket(s) have no head with an email. Nothing was sent.");
            return 0;
        }

        $sent = 0;
        $remindedIds = [];

        foreach ($recipients as $email => $recipient) {
            try {
                Mail::to($email)->queue(new OverdueTicketDigest($recipient['name'], $recipient['ticket_ids']));
                $remindedIds = array_merge($remindedIds, $recipient['ticket_ids']);
                $sent++;
            } catch (\Exception $e) {
                Log::error("SendOverdueTicketReminders: failed to email {$email}: " . $e->getMessage(), ['exception' => $e]);
            }
        }

        // Only tickets that reached at least one head count as reminded, so a failed send is retried on the next run.
        if ($remindedIds) {
            Ticket::whereIn('id', array_unique($remindedIds))->update(['last_overdue_reminder_at' => now()]);
        }

        $message = "{$sent} summary email(s) sent covering " . count(array_unique($remindedIds)) . " overdue ticket(s); {$skipped} skipped with no recipient.";
        Log::info("SendOverdueTicketReminders: {$message}");
        $this->info($message);

        return 0;
    }
}
