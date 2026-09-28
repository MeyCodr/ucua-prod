<x-layouts.guest2-layout>
    <x-slot name="header">
        <div>
            <div style="font-size: 12px; font-weight: 600; color: #9ca3af;">Staff ID: {{ $staff_id }}</div>
            <h2 style="margin: 2px 0 0; font-size: 1.5rem; line-height: 2rem; font-weight: 700; letter-spacing: -0.025em; color: #111827;">My Tickets</h2>
        </div>
    </x-slot>

    @php
        // Every link on this page keeps the staff ID and the current sort.
        $sortLink = fn (string $field) => route('SearchTicketResult', [
            'status' => $status,
            'staff_id' => $staff_id,
            'sort' => $field,
            'direction' => $sortField === $field && $sortDirection === 'asc' ? 'desc' : 'asc',
        ]);
        $columns = [
            'id' => 'Observation',
            'ticket_id' => 'Ticket ID',
            'name' => 'Reported By',
            'department' => 'Department',
            'plant' => 'Plant',
            'created_at' => 'Reported On',
            'dateline' => 'Action Dateline',
        ];
    @endphp

    {{--
        The compiled public/css/app.css on this install predates this markup and can't
        currently be rebuilt (Laravel Mix's toolchain doesn't run on this machine), so this
        page carries its own scoped styles instead of relying on Tailwind utilities.
    --}}
    <style>
        #ticket-list { max-width: 72rem; margin: 0 auto; padding: 24px 16px 48px; }
        #ticket-list .points { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 16px; background: #fff; border: 1px solid #e5e7eb; border-radius: 0.75rem; padding: 20px 24px; box-shadow: 0 1px 2px rgba(20,20,19,0.04); }
        #ticket-list .points-label { font-size: 12px; font-weight: 600; color: #9a9d8f; }
        #ticket-list .points-value { font-size: 28px; line-height: 1.2; font-weight: 700; color: #111827; }
        #ticket-list .points-value small { font-size: 13px; font-weight: 600; color: #9a9d8f; margin-left: 4px; }
        #ticket-list .redeem-btn { display: inline-flex; align-items: center; gap: 8px; padding: 10px 18px; border-radius: 0.5rem; background: #16a34a; color: #fff; font-size: 13px; font-weight: 700; text-decoration: none; }
        #ticket-list .redeem-btn:hover { background: #15803d; color: #fff; }

        #ticket-list .tabs { display: flex; gap: 4px; margin: 20px 0; padding: 4px; background: #eceef1; border-radius: 0.75rem; }
        #ticket-list .tab { flex: 1; text-align: center; padding: 9px 12px; border-radius: 0.5rem; font-size: 13px; font-weight: 600; color: #6b7280; text-decoration: none; }
        #ticket-list .tab:hover { color: #111827; }
        #ticket-list .tab.is-active { background: #fff; color: #4338ca; box-shadow: 0 1px 2px rgba(20,20,19,0.1); }

        #ticket-list .table-wrap { background: #fff; border: 1px solid #e5e7eb; border-radius: 0.75rem; overflow-x: auto; box-shadow: 0 1px 2px rgba(20,20,19,0.04); }
        #ticket-list table { width: 100%; min-width: 760px; border-collapse: collapse; font-size: 13px; }
        #ticket-list thead { background: #f9fafb; }
        #ticket-list th { text-align: left; padding: 14px 16px; font-size: 11px; font-weight: 600; letter-spacing: 0.05em; text-transform: uppercase; color: #6b7280; white-space: nowrap; user-select: none; }
        #ticket-list th a { display: inline-flex; align-items: center; gap: 5px; color: #6b7280; text-decoration: none; }
        #ticket-list th a:hover { color: #1f2937; }
        #ticket-list th.is-sorted a { color: #4338ca; }
        #ticket-list .sort-icon { opacity: 0.45; }
        #ticket-list th.is-sorted .sort-icon { opacity: 1; }
        #ticket-list td { padding: 14px 16px; color: #374151; border-top: 1px solid #f0f1f3; vertical-align: middle; }
        #ticket-list tbody tr.row { cursor: pointer; }
        #ticket-list tbody tr.row:hover { background: #f5f7ff; }
        #ticket-list td.nowrap { white-space: nowrap; }
        #ticket-list .strong { font-weight: 600; color: #111827; }
        #ticket-list .id-link { font-weight: 700; color: #4338ca; text-decoration: none; }
        #ticket-list .id-link:hover { text-decoration: underline; }
        #ticket-list .muted { color: #6b7280; font-size: 12px; overflow-wrap: anywhere; }
        #ticket-list .late { color: #dc2626; font-weight: 600; }
        #ticket-list .late small { display: block; font-size: 11px; font-weight: 600; }

        #ticket-list .empty { text-align: center; padding: 48px 24px !important; color: #6b7280; }
        #ticket-list .empty-icon { width: 44px; height: 44px; margin: 0 auto 12px; border-radius: 12px; background: #eef2ff; color: #4f46e5; display: flex; align-items: center; justify-content: center; }
        #ticket-list .empty-title { font-size: 15px; font-weight: 700; color: #111827; }
        #ticket-list .empty-text { font-size: 13px; margin-top: 2px; }
        #ticket-list .pager { margin-top: 20px; }
        #ticket-list .scroll-hint { display: none; margin-bottom: 8px; font-size: 12px; color: #9ca3af; text-align: center; }

        @media (max-width: 800px) {
            #ticket-list .scroll-hint { display: block; }
        }
        @media (max-width: 520px) {
            #ticket-list .points { padding: 16px; }
            #ticket-list .redeem-btn { width: 100%; justify-content: center; }
        }
    </style>

    <div id="ticket-list">
        {{-- Points --}}
        <div class="points">
            <div>
                <div class="points-label">Current Points</div>
                <div class="points-value">{{ $point ?? 0 }}<small>pts</small></div>
            </div>
            <a href="{{ route('redeem.point', ['staff_id' => $staff_id, 'status' => 'Pending']) }}" class="redeem-btn">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 12v9H4v-9" /><path d="M2 7h20v5H2z" /><path d="M12 21V7" /><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z" /><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z" /></svg>
                Redeem Your Points
            </a>
        </div>

        {{-- Status tabs --}}
        <div class="tabs">
            @foreach ($tabs as $item)
                <a href="{{ $item->link }}?{{ http_build_query(['staff_id' => $staff_id, 'sort' => $sortField, 'direction' => $sortDirection]) }}"
                    class="tab {{ $item->isActive ? 'is-active' : '' }}">
                    {{ $item->name }}
                </a>
            @endforeach
        </div>

        {{-- Tickets --}}
        <div class="scroll-hint">Swipe sideways to see all columns</div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        @foreach ($columns as $field => $label)
                            <th class="{{ $sortField === $field ? 'is-sorted' : '' }}"
                                aria-sort="{{ $sortField === $field ? ($sortDirection === 'asc' ? 'ascending' : 'descending') : 'none' }}">
                                <a href="{{ $sortLink($field) }}">
                                    {{ $label }}
                                    <svg class="sort-icon" width="10" height="10" viewBox="0 0 24 24" fill="none"
                                        stroke="{{ $sortField === $field ? '#4f46e5' : '#98a2b3' }}" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                        @if ($sortField === $field && $sortDirection === 'asc')
                                            <path d="M18 15l-6-6-6 6" />
                                        @elseif ($sortField === $field && $sortDirection === 'desc')
                                            <path d="M6 9l6 6 6-6" />
                                        @else
                                            <path d="M8 9l4-4 4 4" /><path d="M16 15l-4 4-4-4" />
                                        @endif
                                    </svg>
                                </a>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tickets as $item)
                        @php
                            $detailUrl = route('SearchTicketDetail', ['status' => $item->status, 'ticket_id' => $item->id]);
                            $isLate = $item->status === 'Open' && $item->dateline && \Carbon\Carbon::parse($item->dateline)->isPast();
                        @endphp
                        <tr class="row" onclick="window.location='{{ $detailUrl }}'">
                            <td class="nowrap"><a href="{{ $detailUrl }}" class="id-link" onclick="event.stopPropagation()">#{{ $item->id }}</a></td>
                            <td class="nowrap">#{{ $item->ticket_id }}</td>
                            <td>
                                <div class="strong">{{ $item->name }}</div>
                                <div class="muted">{{ $item->email }}</div>
                            </td>
                            <td>{{ $item->department?->name ?? ($item->department_other ?: '—') }}</td>
                            <td>{{ $item->plant_involve?->name ?? '—' }}</td>
                            <td class="nowrap">{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}
                                <div class="muted">{{ \Carbon\Carbon::parse($item->created_at)->format('g:i A') }}</div>
                            </td>
                            <td class="nowrap {{ $isLate ? 'late' : '' }}">
                                @if ($item->dateline)
                                    {{ \Carbon\Carbon::parse($item->dateline)->format('d/m/Y') }}
                                    @if ($isLate)<small>Overdue</small>@endif
                                @else
                                    —
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($columns) }}" class="empty">
                                <div class="empty-icon">
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 3v5h5" /><path d="M6 3h8l5 5v13H6z" /></svg>
                                </div>
                                <div class="empty-title">No tickets here</div>
                                <div class="empty-text">There are no {{ strtolower($status ?? '') }} tickets for this Staff ID.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pager">
            {{ $tickets->links() }}
        </div>
    </div>
</x-layouts.guest2-layout>
