<?php

namespace App\Http\Controllers;

use App\Models\Approval;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\TicketContractor;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function ShowWelcome(Request $request)
    {
        return view('welcome');
    }

    public function ShowDashboard(Request $request)
    {
        $user_role = Auth::user()->groups->pluck('name');

        $approval = collect();
        if ($user_role->contains(function ($role) {
            return in_array($role, ['hodiv', 'hodept', 'hosubdept', 'hop', 'hos']);
        })) {
            $approval = Approval::where([
                ['approver_level', 1],
                ['approver_status', 'Pending']
            ])->pluck('ticket_id');
        } elseif ($user_role->contains(function ($role) {
            return in_array($role, ['admin', 'she_admin']);
        })) {
            $level_1 = Approval::where([
                ['approver_level', 1],
                ['approver_status', 'Verified']
            ])->pluck('ticket_id');

            $approval = Approval::whereIn('ticket_id', $level_1)
                ->where([
                    ['approver_level', 2],
                    ['approver_status', 'Pending']
                ])->pluck('ticket_id');
        } else {
            return redirect()->back()->withErrors(['error' => 'You do not have permission to view this page.']);
        }

        $numTicketsPendingVerify = Ticket::whereIn('id', $approval)->orderBy('created_at', 'desc')->count();
        // get number of pending verify tickets
        // $numTicketsPendingVerify = Approval::where('approver_status', 'Pending')->count();

        $mode = 0;
        $text = null;
        $stats = $this->dashboardStats();

        return view('dashboard', compact('numTicketsPendingVerify', 'mode', 'text', 'stats'));
    }

    /**
     * Figures for the UCUA dashboard. Percentages are all shares of the total number of reports,
     * so Open + Closed + Declined (and Unsafe Condition + Unsafe Act) add up to 100%.
     * Monthly and per-plant charts cover the current year (YTD); everything else is to date.
     */
    private function dashboardStats(): array
    {
        $year = now()->year;
        $pct = fn ($n, $of) => $of > 0 ? round($n * 100 / $of, 1) : 0;

        $total = Ticket::count();
        $byType = Ticket::selectRaw('ucua_id, count(*) as n')->groupBy('ucua_id')->pluck('n', 'ucua_id');
        $byStatus = Ticket::selectRaw('status, count(*) as n')->groupBy('status')->pluck('n', 'status');

        $unsafeCondition = (int) ($byType['unsafe_condition'] ?? 0);
        $unsafeAct = (int) ($byType['unsafe_act'] ?? 0);
        $open = (int) ($byStatus['Open'] ?? 0);
        $closed = (int) ($byStatus['Closed'] ?? 0);
        $declined = (int) ($byStatus['Declined'] ?? 0);

        // Monthly, Jan .. current month of this year, split by unsafe condition / act.
        $monthly = Ticket::whereYear('created_at', $year)
            ->selectRaw('month(created_at) as m, ucua_id, count(*) as n')
            ->groupBy('m', 'ucua_id')
            ->get();
        $months = collect(range(1, now()->month))->map(function ($m) use ($monthly) {
            $rows = $monthly->where('m', $m);
            return [
                'label' => date('M', mktime(0, 0, 0, $m, 1)),
                'condition' => (int) $rows->where('ucua_id', 'unsafe_condition')->sum('n'),
                'act' => (int) $rows->where('ucua_id', 'unsafe_act')->sum('n'),
                'total' => (int) $rows->sum('n'),
            ];
        });

        // Reports per plant involved. Tickets pointing at a plant that no longer exists are grouped as "Not recorded".
        $plantCounts = fn ($query) => $query
            ->leftJoin('plants', 'plants.id', '=', 'entry_tickets.plant_inv_id')
            ->selectRaw("coalesce(plants.name, 'Not recorded') as name, count(*) as n")
            ->groupBy('entry_tickets.plant_inv_id', 'plants.name')
            ->orderByDesc('n')->orderBy('name')
            ->get();
        $plantsYtd = $plantCounts(Ticket::whereYear('entry_tickets.created_at', $year));
        $plantsAll = $plantCounts(Ticket::query())->where('name', '!=', 'Not recorded')->values();
        $topPlantCount = (int) ($plantsAll->first()->n ?? 0);
        $topPlants = $plantsAll->where('n', $topPlantCount)->pluck('name');

        $stop = Ticket::leftJoin('stop_cultures', 'stop_cultures.id', '=', 'entry_tickets.stop_cult_id')
            ->selectRaw("coalesce(stop_cultures.name, 'Not recorded') as name, stop_cultures.description, count(*) as n")
            ->groupBy('entry_tickets.stop_cult_id', 'stop_cultures.name', 'stop_cultures.description')
            ->orderByDesc('n')
            ->get();

        // Top contributors by Staff ID; name/department come from their latest report.
        $contributors = Ticket::selectRaw("staff_id, count(*) as n, sum(status = 'Closed') as closed, max(id) as latest_id")
            ->groupBy('staff_id')
            ->orderByDesc('n')
            ->limit(10)
            ->get();
        $latest = Ticket::with('department')->whereIn('id', $contributors->pluck('latest_id'))->get()->keyBy('id');
        $contributors = $contributors->map(fn ($c) => [
            'staff_id' => $c->staff_id,
            'name' => $latest[$c->latest_id]->name ?? '-',
            'department' => $latest[$c->latest_id]->department->name ?? ($latest[$c->latest_id]->department_other ?? '-'),
            'reports' => (int) $c->n,
            'closed' => (int) $c->closed,
        ]);

        return [
            'year' => $year,
            'total' => $total,
            'unsafe_condition' => ['n' => $unsafeCondition, 'pct' => $pct($unsafeCondition, $total)],
            'unsafe_act' => ['n' => $unsafeAct, 'pct' => $pct($unsafeAct, $total)],
            'open' => ['n' => $open, 'pct' => $pct($open, $total)],
            'closed' => ['n' => $closed, 'pct' => $pct($closed, $total)],
            'declined' => ['n' => $declined, 'pct' => $pct($declined, $total)],
            'top_plants' => $topPlants,
            'top_plant_count' => $topPlantCount,
            'months' => $months,
            'plants_ytd' => $plantsYtd,
            'stop' => $stop,
            'contributors' => $contributors,
        ];
    }
}
