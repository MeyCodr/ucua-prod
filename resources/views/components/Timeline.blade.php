@php
    $level3Attachments = $ticket->attachment->where('level', 3);
    $hasLevel3Attachments = $level3Attachments->count() > 0;

    // The level-1 approval's group_id is a static default (always "hodept"), not the
    // specific people this ticket was actually routed to. Resolve the real PICs for
    // this ticket instead of listing every member of that group. Head of department (and
    // sub-department head) only - keep in sync with the new-ticket email in TicketController.
    $level1Pics = collect([
        optional($ticket->dep_responsible)->head_department,
        optional($ticket->sub_dep_responsible)->head_subdepartment,
    ])->filter()->unique('id')->values();

    $reviewSteps = collect();
    for ($i = 1; $i <= 4; $i++) {
        foreach ($ticket->approval->where('approver_level', $i) as $each) {
            $reviewSteps->push(['level' => $i, 'approval' => $each]);
        }
    }

    $statusStyles = [
        'Verified' => ['bg' => '#DCFCE7', 'fg' => '#16803D', 'pill' => '#DCFCE7', 'pillFg' => '#16803D', 'shape' => 'check'],
        'Completed' => ['bg' => '#DCFCE7', 'fg' => '#16803D', 'pill' => '#DCFCE7', 'pillFg' => '#16803D', 'shape' => 'check'],
        'Declined' => ['bg' => '#FDE2DC', 'fg' => '#9A3412', 'pill' => '#FDE2DC', 'pillFg' => '#9A3412', 'shape' => 'x'],
        'Disabled' => ['bg' => '#F1EFE7', 'fg' => '#6B7280', 'pill' => '#F1EFE7', 'pillFg' => '#6B7280', 'shape' => 'dash'],
    ];
@endphp

{{--
    The compiled public/css/app.css on this install predates this markup and can't
    currently be rebuilt (Laravel Mix's toolchain doesn't run on this machine), so a
    handful of Tailwind utilities below were never generated. These rules backfill
    exactly those classes with their standard Tailwind values, scoped to this component
    so it renders consistently wherever it's included.
--}}
<style>
    .review-timeline .card { background: #fff; border: 1px solid #e5e7eb; border-radius: 0.75rem; padding: 28px; box-shadow: 0 1px 2px rgba(20,20,19,0.04); }
    .review-timeline .card-head { display: flex; align-items: center; gap: 10px; margin-bottom: 22px; padding-bottom: 16px; border-bottom: 1px solid #f0efe9; }
    .review-timeline .card-icon { width: 32px; height: 32px; border-radius: 10px; background: #eef2ff; color: #4f46e5; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .review-timeline .card-title { margin: 0; font-size: 13px; font-weight: 700; color: #14171a; text-transform: uppercase; letter-spacing: 0.04em; }
    .review-timeline .field-label { font-size: 12px; color: #9a9d8f; font-weight: 600; }
    .review-timeline .gap-4 { gap: 1rem; }
    .review-timeline .gap-8 { gap: 2rem; }
    .review-timeline .gap-2 { gap: 0.5rem; }
    .review-timeline .px-2\.5 { padding-left: 0.625rem; padding-right: 0.625rem; }
    .review-timeline .py-0\.5 { padding-top: 0.125rem; padding-bottom: 0.125rem; }
    .review-timeline .pb-6 { padding-bottom: 1.5rem; }
    .review-timeline .mb-3 { margin-bottom: 0.75rem; }
    .review-timeline .mt-1 { margin-top: 0.25rem; }
    .review-timeline .mt-2 { margin-top: 0.5rem; }
    .review-timeline .rounded-xl { border-radius: 0.75rem; }
</style>

<div class="review-timeline">
    <div class="card">
        <div class="card-head">
            <div class="card-icon">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9" /><path d="M12 7v5l3 3" /></svg>
            </div>
            <h3 class="card-title">Review Timeline</h3>
        </div>

        <div class="grid md:grid-cols-3 gap-8 items-start">
            <div class="md:col-span-2">
                @forelse ($reviewSteps as $step)
                    @php
                        $i = $step['level'];
                        $each = $step['approval'];
                        // When an admin assigned the level-1 approvers by hand, show them instead of
                        // the routed heads.
                        $assignedApprovers = $i == 1 ? $each->assignees->sortBy('name')->values() : collect();
                        $approverUsers = $assignedApprovers->isNotEmpty()
                            ? $assignedApprovers
                            : ($i == 1 ? $level1Pics : $each->group->users);
                        $meta = $statusStyles[$each->approver_status] ?? ['bg' => '#FEF3C7', 'fg' => '#92400E', 'pill' => '#FEF3C7', 'pillFg' => '#92400E', 'shape' => 'dot'];
                    @endphp
                    <div class="flex gap-4">
                        <div class="flex flex-col items-center flex-shrink-0">
                            <div class="flex items-center justify-center rounded-full"
                                style="width: 30px; height: 30px; background: {{ $meta['bg'] }}; color: {{ $meta['fg'] }}; {{ $meta['shape'] === 'dot' ? 'border: 2px solid ' . $meta['fg'] . ';' : '' }}">
                                @if ($meta['shape'] === 'check')
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5" /></svg>
                                @elseif ($meta['shape'] === 'x')
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6L6 18" /><path d="M6 6l12 12" /></svg>
                                @elseif ($meta['shape'] === 'dash')
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14" /></svg>
                                @else
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="1" /></svg>
                                @endif
                            </div>
                            @unless ($loop->last)
                                <div style="width: 2px; flex: 1; min-height: 28px; background: #e8e5db; margin: 4px 0;"></div>
                            @endunless
                        </div>

                        <div class="{{ $loop->last ? '' : 'pb-6' }}">
                            <div class="field-label" style="text-transform: uppercase; letter-spacing: 0.05em;">
                                Level {{ $i }} &middot; {{ $each->group->name_display }}
                            </div>

                            <div class="text-xs font-semibold text-gray-800 mt-1">
                                @forelse ($approverUsers as $item)
                                    {{ $item->name }} <span class="text-gray-400 font-normal">&middot; {{ $item->email }}</span>@if (!$loop->last)<br>@endif
                                @empty
                                    <span class="text-gray-500 font-normal">No head assigned</span>
                                @endforelse
                            </div>

                            @if ($assignedApprovers->isNotEmpty())
                                <div class="text-xs text-gray-500 mt-1">
                                    Assigned by admin{{ $each->assignedBy ? ' (' . $each->assignedBy->name . ')' : '' }}
                                </div>
                            @endif

                            <div class="flex items-center gap-2 mt-2">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-bold" style="background: {{ $meta['pill'] }}; color: {{ $meta['pillFg'] }};">
                                    {{ $each->approver_status }}
                                </span>
                                @if ($each->respond_at)
                                    <span class="text-xs text-gray-500">On {{ \Carbon\Carbon::parse($each->respond_at)->format('d/m/Y, g:i A') }}</span>
                                @endif
                            </div>

                            @if ($each->approver_remark)
                                <div class="mt-2 rounded-lg text-xs text-gray-700" style="background: #f6f5f0; padding: 10px 12px; line-height: 1.5;">
                                    {{ $each->approver_remark }}
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-xs text-gray-500">No review activity yet.</div>
                @endforelse
            </div>

            @if ($hasLevel3Attachments)
                <div class="md:col-span-1">
                    <div class="field-label mb-3" style="text-transform: uppercase; letter-spacing: 0.05em;">Corrective Action Evidence</div>
                    @component('components.Carousel', [
                        'attachments' => $level3Attachments,
                        'ticket' => $ticket,
                        'approverLevel' => 0,
                    ])
                    @endcomponent
                </div>
            @endif
        </div>
    </div>
</div>
