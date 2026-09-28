<x-app-layout>
    <x-slot name="header">
        @php
            $statusStyles = [
                'In Progress' => ['bg' => '#FEF3C7', 'fg' => '#92400E', 'dot' => '#F59E0B'],
                'Declined' => ['bg' => '#FDE2DC', 'fg' => '#9A3412', 'dot' => '#DC5A3C'],
            ];
            $statusStyle = $statusStyles[$ticket->status] ?? ['bg' => '#DCFCE7', 'fg' => '#16803D', 'dot' => '#22A557'];
        @endphp
        <div class="flex flex-col gap-3">
            <a href="{{ route('ShowAllSubmissions') }}"
                class="inline-flex items-center gap-2 text-sm font-semibold text-gray-500 hover:text-gray-700 transition duration-150 ease-in-out flex-shrink-0" style="align-self: flex-start;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 12H5" /><path d="M12 19l-7-7 7-7" />
                </svg>
                All Submissions
            </a>
            <div>
                <div class="text-xs font-bold uppercase tracking-wider text-gray-400">Ticket {{ $ticket->ticket_id }}</div>
                <div class="flex items-center gap-3 mt-0.5">
                    <h2 class="text-2xl font-bold tracking-tight text-gray-900 leading-tight">
                        Observation #{{ $ticket->id }}
                    </h2>
                    <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-bold"
                        style="background: {{ $statusStyle['bg'] }}; color: {{ $statusStyle['fg'] }};">
                        <span class="w-1.5 h-1.5 rounded-full" style="background: {{ $statusStyle['dot'] }};"></span>
                        {{ $ticket->status }}
                    </span>
                </div>
            </div>
        </div>
    </x-slot>

    {{--
        The compiled public/css/app.css on this install predates this markup and can't
        currently be rebuilt (Laravel Mix's toolchain doesn't run on this machine), so a
        handful of Tailwind utilities below were never generated. These rules backfill
        exactly those classes with their standard Tailwind values.
    --}}
    <style>
        .tracking-wider { letter-spacing: 0.05em; }
        .tracking-tight { letter-spacing: -0.025em; }
        .text-2xl { font-size: 1.5rem; line-height: 2rem; }
        .rounded-xl { border-radius: 0.75rem; }
        .w-1\.5 { width: 0.375rem; }
        .h-1\.5 { height: 0.375rem; }
        .w-8 { width: 2rem; }
        .h-8 { height: 2rem; }
        .gap-1\.5 { gap: 0.375rem; }
        .gap-2 { gap: 0.5rem; }
        .gap-3 { gap: 0.75rem; }
        .gap-4 { gap: 1rem; }
        .gap-5 { gap: 1.25rem; }
        .gap-7 { gap: 1.75rem; }
        .gap-8 { gap: 2rem; }
        .mb-5 { margin-bottom: 1.25rem; }
        .mb-6 { margin-bottom: 1.5rem; }
        .pt-6 { padding-top: 1.5rem; }
        .bg-indigo-50 { background-color: #eef2ff; }
        .text-indigo-700 { color: #4338ca; }

        #ticket-detail .fotorama { max-width: 100%; }
        #ticket-detail .field-row { display: flex; justify-content: space-between; gap: 12px; }
        #ticket-detail .field-row + .field-row { margin-top: 16px; }
        #ticket-detail .field-label { font-size: 12px; color: #9a9d8f; font-weight: 600; flex-shrink: 0; line-height: 1.4; }
        #ticket-detail .field-value { font-size: 12px; color: #24271f; font-weight: 600; text-align: right; line-height: 1.5; }
        #ticket-detail .card { background: #fff; border: 1px solid #e5e7eb; border-radius: 0.75rem; padding: 28px; box-shadow: 0 1px 2px rgba(20,20,19,0.04); }
        #ticket-detail .card-head { display: flex; align-items: center; gap: 10px; margin-bottom: 22px; padding-bottom: 16px; border-bottom: 1px solid #f0efe9; }
        #ticket-detail .card-icon { width: 32px; height: 32px; border-radius: 10px; background: #eef2ff; color: #4f46e5; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        #ticket-detail .card-title { margin: 0; font-size: 13px; font-weight: 700; color: #14171a; text-transform: uppercase; letter-spacing: 0.04em; }
        #ticket-detail .assign-form { display: flex; flex-wrap: wrap; align-items: flex-start; gap: 10px; margin-top: 16px; }
        #ticket-detail .assign-form button[type="submit"] { border: 0; border-radius: 0.5rem; padding: 9px 18px; font-size: 13px; font-weight: 700; color: #fff; background: #4f46e5; cursor: pointer; }
        #ticket-detail .assign-form button[type="submit"]:hover { background: #4338ca; }

        /* Searchable multi-select. The panel is an always-visible list until the script upgrades it. */
        #ticket-detail .ms { position: relative; flex: 1 1 320px; max-width: 560px; }
        #ticket-detail .ms-control { display: flex; flex-wrap: wrap; align-items: center; gap: 6px; min-height: 40px; padding: 5px 10px; border: 1px solid #d1d5db; border-radius: 0.5rem; background: #fff; cursor: text; }
        #ticket-detail .ms-open .ms-control { border-color: #6366f1; box-shadow: 0 0 0 2px rgba(99,102,241,0.2); }
        #ticket-detail .ms-chips { display: contents; }
        #ticket-detail .ms-chip { display: inline-flex; align-items: center; gap: 4px; padding: 2px 4px 2px 10px; border-radius: 9999px; background: #eef2ff; color: #4338ca; font-size: 12px; font-weight: 600; }
        #ticket-detail .ms-chip button { border: 0; background: transparent; color: inherit; font-size: 15px; line-height: 1; padding: 0 5px; cursor: pointer; }
        #ticket-detail .ms-search, #ticket-detail .ms-search:focus { flex: 1 1 140px; min-width: 120px; border: 0; outline: 0; box-shadow: none; padding: 3px 2px; font-size: 13px; background: transparent; }
        #ticket-detail .ms-panel { margin-top: 6px; max-height: 240px; overflow-y: auto; border: 1px solid #d1d5db; border-radius: 0.5rem; background: #fff; }
        #ticket-detail .ms-js .ms-panel { display: none; position: absolute; top: 100%; left: 0; right: 0; z-index: 30; margin-top: 4px; box-shadow: 0 8px 24px rgba(0,0,0,0.12); }
        #ticket-detail .ms-js.ms-open .ms-panel { display: block; }
        #ticket-detail .ms-option { display: flex; align-items: flex-start; gap: 10px; padding: 8px 12px; cursor: pointer; margin: 0; }
        #ticket-detail .ms-option[hidden], #ticket-detail .ms-empty[hidden] { display: none; }
        #ticket-detail .ms-option:hover { background: #f5f5ff; }
        #ticket-detail .ms-option input { margin-top: 3px; accent-color: #4f46e5; }
        #ticket-detail .ms-name { display: block; font-size: 13px; font-weight: 600; color: #24271f; }
        #ticket-detail .ms-role { display: block; font-size: 12px; color: #9a9d8f; }
        #ticket-detail .ms-empty { padding: 10px 12px; font-size: 12px; color: #9a9d8f; }
    </style>

    <div id="ticket-detail" class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 md:mb-5">
            {{-- Alert message pop up --}}
            @if (session('alertColor'))
                @component('components.Alert')
                @slot('alertColor')
                {{ session('alertColor') }}
                @endslot
                @slot('message')
                {{ session('message') }}
                @endslot
                @endcomponent
            @endif

            <script src="{{ asset('jquery/3.4.1/jquery.min.js') }}"></script>
            <link href="{{ asset('fotorama/fotorama.css') }}" rel="stylesheet">
            <script src="{{ asset('fotorama/fotorama.js') }}"></script>

            @php
                $initials = function (?string $name) {
                    $parts = array_filter(preg_split('/\s+/', trim((string) $name)));
                    $letters = array_map(fn ($p) => mb_substr($p, 0, 1), array_slice($parts, 0, 2));
                    return mb_strtoupper(implode('', $letters)) ?: '—';
                };
            @endphp

            {{-- Quick stats --}}
            <div class="grid md:grid-cols-3 gap-5 mb-6">
                <div class="card">
                    <div class="field-label">Ticket ID</div>
                    <div class="text-sm font-bold text-gray-900 mt-1">{{ $ticket->ticket_id }}</div>
                </div>
                <div class="card">
                    <div class="field-label">Action Dateline</div>
                    <div class="text-sm font-bold {{ \Carbon\Carbon::parse($ticket->dateline)->isPast() ? 'text-red-600' : 'text-gray-900' }} mt-1">
                        {{ \Carbon\Carbon::parse($ticket->dateline)->format('d/m/Y') }}
                    </div>
                </div>
                <div class="card">
                    <div class="field-label">Reported On</div>
                    <div class="text-sm font-bold text-gray-900 mt-1">
                        {{ \Carbon\Carbon::parse($ticket->created_at)->format('d/m/Y, g:i A') }}
                    </div>
                </div>
            </div>

            {{-- Level-1 approvers: admins can assign one or more people by hand while the ticket waits at level 1 --}}
            @php $assignees = $level1Approval ? $level1Approval->assignees->sortBy('name')->values() : collect(); @endphp
            @if ($canAssign || $assignees->isNotEmpty())
                <div class="card mb-6">
                    <div class="card-head">
                        <div class="card-icon">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" /><circle cx="9" cy="7" r="4" /><path d="M19 8v6" /><path d="M22 11h-6" /></svg>
                        </div>
                        <h3 class="card-title">Level-1 Approver</h3>
                    </div>

                    @if ($assignees->isNotEmpty())
                        <div class="text-xs text-gray-700">
                            Assigned to
                            @foreach ($assignees as $person)
                                <span class="font-bold text-gray-900">{{ $person->name }}</span>
                                <span class="text-gray-500">({{ $person->email }})</span>@if (!$loop->last), @endif
                            @endforeach
                        </div>
                        <div class="text-xs text-gray-500 mt-1">
                            @if ($level1Approval->assignedBy)
                                by {{ $level1Approval->assignedBy->name }}
                            @endif
                            @if ($level1Approval->assigned_at)
                                on {{ \Carbon\Carbon::parse($level1Approval->assigned_at)->format('d/m/Y, g:i A') }}
                            @endif
                            &mdash; only {{ $assignees->count() > 1 ? 'these people' : 'this person' }} (or an admin) can verify or decline this ticket.
                        </div>
                    @else
                        <div class="text-xs text-gray-500">Not assigned. Any of the routed heads can verify this ticket.</div>
                    @endif

                    @if ($canAssign)
                        <form method="POST" action="{{ route('AssignApprover', ['ticketId' => $ticket->id]) }}" class="assign-form">
                            @csrf
                            <div class="ms" id="assignPicker">
                                <div class="ms-control">
                                    <span class="ms-chips"></span>
                                    <input type="text" class="ms-search" placeholder="Search name, role or email&hellip;" autocomplete="off" aria-label="Search approvers">
                                </div>
                                <div class="ms-panel">
                                    @foreach ($assignableUsers as $user)
                                        <label class="ms-option" data-search="{{ mb_strtolower($user->name . ' ' . $user->role_label . ' ' . $user->email) }}">
                                            <input type="checkbox" name="approver_ids[]" value="{{ $user->id }}" data-name="{{ $user->name }}"
                                                {{ $assignees->contains('id', $user->id) ? 'checked' : '' }}>
                                            <span>
                                                <span class="ms-name">{{ $user->name }}</span>
                                                <span class="ms-role">{{ $user->role_label }}</span>
                                            </span>
                                        </label>
                                    @endforeach
                                    <div class="ms-empty" hidden>No matching person</div>
                                </div>
                            </div>
                            <button type="submit">{{ $assignees->isNotEmpty() ? 'Save' : 'Assign' }}</button>
                        </form>
                        <div class="text-xs text-gray-400 mt-2">Select one or more people. Leave it empty and save to clear the assignment.</div>

                        <script>
                            (function () {
                                var root = document.getElementById('assignPicker');
                                if (!root) return;
                                root.classList.add('ms-js');

                                var control = root.querySelector('.ms-control');
                                var chips = root.querySelector('.ms-chips');
                                var search = root.querySelector('.ms-search');
                                var empty = root.querySelector('.ms-empty');
                                var options = [].slice.call(root.querySelectorAll('.ms-option'));

                                function filter() {
                                    var q = search.value.trim().toLowerCase();
                                    var shown = 0;
                                    options.forEach(function (o) {
                                        var match = !q || o.getAttribute('data-search').indexOf(q) !== -1;
                                        o.hidden = !match;
                                        if (match) shown++;
                                    });
                                    empty.hidden = shown !== 0;
                                }

                                function open() { root.classList.add('ms-open'); }
                                function close() { root.classList.remove('ms-open'); search.value = ''; filter(); }

                                function renderChips() {
                                    chips.innerHTML = '';
                                    options.forEach(function (o) {
                                        var box = o.querySelector('input');
                                        if (!box.checked) return;
                                        var chip = document.createElement('span');
                                        chip.className = 'ms-chip';
                                        chip.appendChild(document.createTextNode(box.getAttribute('data-name')));
                                        var x = document.createElement('button');
                                        x.type = 'button';
                                        x.textContent = '×';
                                        x.setAttribute('aria-label', 'Remove ' + box.getAttribute('data-name'));
                                        x.addEventListener('click', function (e) {
                                            e.stopPropagation();
                                            box.checked = false;
                                            renderChips();
                                        });
                                        chip.appendChild(x);
                                        chips.appendChild(chip);
                                    });
                                }

                                control.addEventListener('click', function () { search.focus(); open(); });
                                search.addEventListener('focus', open);
                                search.addEventListener('input', function () { open(); filter(); });
                                search.addEventListener('keydown', function (e) {
                                    if (e.key === 'Enter') {
                                        // Enter picks the first match instead of submitting the form.
                                        e.preventDefault();
                                        var first = options.filter(function (o) { return !o.hidden; })[0];
                                        if (first) {
                                            var box = first.querySelector('input');
                                            box.checked = !box.checked;
                                            renderChips();
                                        }
                                    }
                                });
                                options.forEach(function (o) {
                                    o.querySelector('input').addEventListener('change', renderChips);
                                });
                                document.addEventListener('click', function (e) {
                                    if (!root.contains(e.target)) close();
                                });
                                root.addEventListener('keydown', function (e) {
                                    if (e.key === 'Escape') close();
                                });

                                renderChips();
                            })();
                        </script>
                    @endif
                </div>
            @endif

            <div class="grid md:grid-cols-3 gap-5 items-start">
                {{-- LEFT COLUMN --}}
                <div class="flex flex-col gap-5">
                    {{-- Reported By --}}
                    <div class="card">
                        <div class="card-head">
                            <div class="card-icon">
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4" /><path d="M4 21v-1a8 8 0 0 1 16 0v1" /></svg>
                            </div>
                            <h3 class="card-title">Reported By</h3>
                        </div>

                        <div class="flex items-center gap-3 mb-5">
                            <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-indigo-50 text-indigo-700 text-xs font-bold flex-shrink-0">
                                {{ $initials($ticket->name) }}
                            </span>
                            <div>
                                <div class="text-xs font-bold text-gray-900">{{ $ticket->name }}</div>
                                <div class="text-xs text-gray-500 mt-0.5">{{ $ticket->email }}</div>
                            </div>
                        </div>

                        <div class="field-row">
                            <span class="field-label">Staff ID</span>
                            <span class="field-value">{{ $ticket->staff_id }}</span>
                        </div>
                        <div class="field-row">
                            <span class="field-label">Phone</span>
                            <span class="field-value">{{ $ticket->phone_number }}</span>
                        </div>
                        <div class="field-row">
                            <span class="field-label">Department</span>
                            <span class="field-value">
                                @if ($ticket->department_id != 0)
                                    {{ $ticket->department?->name }}
                                    @if ($ticket->sub_department_id != 0)
                                        <br>{{ $ticket->sub_department?->name }}
                                    @endif
                                @else
                                    {{ $ticket->department_other }}
                                @endif
                            </span>
                        </div>
                    </div>

                    {{-- Location & Responsibility --}}
                    <div class="card">
                        <div class="card-head">
                            <div class="card-icon">
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0z" /><circle cx="12" cy="10" r="3" /></svg>
                            </div>
                            <h3 class="card-title">Location &amp; Responsibility</h3>
                        </div>

                        <div class="flex flex-col gap-4">
                            <div>
                                <div class="field-label mb-1">Affected Area</div>
                                <div class="text-xs font-semibold text-gray-800">{{ $ticket->affected_area }}</div>
                            </div>
                            <div>
                                <div class="field-label mb-1">Plant Involved</div>
                                <div class="text-xs font-semibold text-gray-800">{{ $ticket->plant_involve?->name ?? '—' }}</div>
                            </div>
                            <div class="pt-4 border-t border-gray-100">
                                <div class="field-label mb-1">Department Responsible</div>
                                <div class="text-xs font-semibold text-gray-800">
                                    @if ($ticket->dept_res_id != 0)
                                        {{ $ticket->dep_responsible?->name }}
                                        @if ($ticket->sub_dept_res_id != 0)
                                            <br>{{ $ticket->sub_dep_responsible?->name }}
                                        @endif
                                    @else
                                        {{ $ticket->dept_res_other }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Approval Chain --}}
                    <div class="card">
                        <div class="card-head">
                            <div class="card-icon">
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" /><circle cx="9" cy="7" r="4" /><path d="M22 21v-2a4 4 0 0 0-3-3.87" /><path d="M16 3.13a4 4 0 0 1 0 7.75" /></svg>
                            </div>
                            <h3 class="card-title">Approval Chain</h3>
                        </div>

                        <div class="flex flex-col gap-4">
                            <div>
                                <div class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-1">HOP Involved</div>
                                @if ($ticket->plant_involve)
                                    <div class="text-xs font-semibold text-gray-800">{{ $ticket->plant_involve?->head_plant?->name ?? 'No item' }}</div>
                                    <div class="text-xs text-gray-500 mt-0.5">{{ $ticket->plant_involve?->head_plant?->email ?? '' }}</div>
                                @else
                                    <div class="text-xs text-gray-500">No item</div>
                                @endif
                            </div>
                            <div class="pt-4 border-t border-gray-100">
                                <div class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-1">Head of Department Responsible</div>
                                @if ($ticket->sub_dept_res_id == 0)
                                    <div class="text-xs font-semibold text-gray-800">{{ $ticket->dep_responsible?->head_department?->name ?? 'No item' }}</div>
                                    <div class="text-xs text-gray-500 mt-0.5">{{ $ticket->dep_responsible?->head_department?->email ?? '' }}</div>
                                @elseif ($ticket->sub_dept_res_id != 0)
                                    <div class="text-xs font-semibold text-gray-800">{{ $ticket->sub_dep_responsible?->head_subdepartment?->name ?? 'No item' }}</div>
                                    <div class="text-xs text-gray-500 mt-0.5">{{ $ticket->sub_dep_responsible?->head_subdepartment?->email ?? '' }}</div>
                                @else
                                    <div class="text-xs text-gray-500">No item</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- RIGHT COLUMN --}}
                <div class="md:col-span-2 flex flex-col gap-5">
                    {{-- Photo Evidence --}}
                    <div class="card">
                        <div class="card-head">
                            <div class="card-icon">
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="7" width="18" height="13" rx="2" /><path d="M9 7l1.5-3h3L15 7" /><circle cx="12" cy="13.5" r="3.5" /></svg>
                            </div>
                            <h3 class="card-title">Photo Evidence</h3>
                        </div>

                        <div class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-3">Before Action Taken</div>
                        <div class="mb-6">
                            @component('components.Carousel', [
                                'attachments' => $ticket->attachment->where('level', 1),
                                'ticket' => $ticket,
                                'approverLevel' => 0,
                            ])
                            @endcomponent
                        </div>

                        @if ($ticket->attachment->where('level', 2)->count() > 0)
                            <div class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-3 pt-4 border-t border-gray-100">Corrective Action Taken by Submitter</div>
                            <div>
                                @component('components.Carousel', [
                                    'attachments' => $ticket->attachment->where('level', 2),
                                    'ticket' => $ticket,
                                    'approverLevel' => 0,
                                ])
                                @endcomponent
                            </div>
                        @endif
                    </div>

                    {{-- Incident Details --}}
                    <div class="card">
                        <div class="card-head">
                            <div class="card-icon">
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 3v5h5" /><path d="M6 3h8l5 5v13H6z" /><path d="M9 13h6" /><path d="M9 17h6" /></svg>
                            </div>
                            <h3 class="card-title">Incident Details</h3>
                        </div>

                        <div class="grid md:grid-cols-2 gap-7">
                            <div>
                                <div class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-2">Description</div>
                                <p class="text-xs leading-relaxed text-gray-700">{{ $ticket->description }}</p>
                            </div>
                            <div>
                                <div class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-2">Action Taken</div>
                                <p class="text-xs leading-relaxed text-gray-700">{{ $ticket->action_taken }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Classification --}}
                    <div class="card">
                        <div class="card-head">
                            <div class="card-icon">
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3l10 18H2z" /><path d="M12 9v5" /><path d="M12 17h.01" /></svg>
                            </div>
                            <h3 class="card-title">Classification</h3>
                        </div>

                        <div class="flex flex-wrap gap-2 mb-5">
                            @if ($ticket->ucua_id == 'unsafe_condition')
                                <span class="inline-flex items-center rounded-lg px-3 py-2 text-xs font-bold" style="background:#FDEEE7; color:#9A3412;">Unsafe Condition</span>
                            @elseif ($ticket->ucua_id == 'unsafe_act')
                                <span class="inline-flex items-center rounded-lg px-3 py-2 text-xs font-bold" style="background:#FDEEE7; color:#9A3412;">Unsafe Act</span>
                            @endif
                            <span class="inline-flex items-center rounded-lg px-3 py-2 text-xs font-semibold bg-gray-100 text-gray-600">
                                @if ($ticket->ucua_type != 0)
                                    {{ $ticket->unsafe_cond_act?->name }}
                                @else
                                    {{ $ticket->ucua_other }}
                                @endif
                            </span>
                        </div>

                        <div class="flex gap-8 pt-4 border-t border-gray-100">
                            <div>
                                <div class="field-label mb-1">BBS Action Taken</div>
                                @if ($ticket->bbs_action == 1)
                                    <span class="inline-flex items-center gap-1.5 text-xs font-bold text-green-700">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5" /></svg>
                                        Yes
                                    </span>
                                @else
                                    <span class="text-xs font-bold text-gray-500">No</span>
                                @endif
                            </div>
                            @if ($ticket->bbs_action == 1)
                                <div>
                                    <div class="field-label mb-1">6C Methodology</div>
                                    <div class="text-xs font-bold text-gray-800">{{ $ticket->bbs_methodology }}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 pt-6">
            <div>
                @component('components.Timeline', [
                    'attachments' => $ticket->attachment->where('level', 3),
                    'ticket' => $ticket,
                    'approverLevel' => 1,
                ])
                @endcomponent
            </div>
        </div>
    </div>
</x-app-layout>
