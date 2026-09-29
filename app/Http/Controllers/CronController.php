<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

/**
 * Lets an external crontab run scheduled jobs over HTTP, for hosting without shell access.
 * Every request must carry the CRON_KEY from .env, in an "X-Cron-Key" header (preferred:
 * it stays out of web server access logs) or a "key" query parameter.
 */
class CronController extends Controller
{
    public function overdueReminders(Request $request)
    {
        $expected = (string) config('services.cron.key');
        $given = (string) ($request->header('X-Cron-Key') ?? $request->query('key', ''));

        // With no key configured the endpoint stays switched off.
        if ($expected === '' || !hash_equals($expected, $given)) {
            abort(404);
        }

        // Sending a batch of summary emails over SMTP can outlast PHP's default 30s limit.
        @set_time_limit(300);

        $exitCode = Artisan::call('tickets:send-overdue-reminders', [
            '--dry-run' => $request->boolean('dry_run'),
        ]);

        return response(Artisan::output(), $exitCode === 0 ? 200 : 500)
            ->header('Content-Type', 'text/plain; charset=UTF-8');
    }
}
