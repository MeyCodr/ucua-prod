<x-layouts.guest2-layout>
    <x-slot name="header">
        <div style="display: flex; flex-direction: column; gap: 12px;">
            <a href="{{ route('SearchTicketResult', ['status' => 'Open', 'staff_id' => $staff_id]) }}" class="page-back">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5" /><path d="M12 19l-7-7 7-7" /></svg>
                My Tickets
            </a>
            <div>
                <div style="font-size: 12px; font-weight: 600; color: #9ca3af;">Staff ID: {{ $staff_id }}</div>
                <h2 style="margin: 2px 0 0; font-size: 1.5rem; line-height: 2rem; font-weight: 700; letter-spacing: -0.025em; color: #111827;">Point Redemption</h2>
            </div>
        </div>
    </x-slot>

    @php
        // Every link keeps the current sort.
        $sortLink = fn (string $field) => route('redeem.point', [
            'staff_id' => $staff_id,
            'status' => request()->route('status'),
            'sort' => $field,
            'direction' => $sortField === $field && $sortDirection === 'asc' ? 'desc' : 'asc',
        ]);
        $isApprovedTab = request()->route('status') !== 'Pending';
        $columns = ['points' => 'Points to Redeem', 'created_at' => 'Requested On'];
        if ($isApprovedTab) {
            $columns['respond_at'] = 'Approved On';
        }
        // Rewards on offer: points => voucher value. The Submit modal offers the same three.
        $rewards = [10 => 'RM20', 50 => 'RM100', 100 => 'RM200'];
        $balance = $point_balance ?? 0;
    @endphp

    {{--
        The compiled public/css/app.css on this install predates this markup and can't
        currently be rebuilt (Laravel Mix's toolchain doesn't run on this machine), so this
        page carries its own scoped styles instead of relying on Tailwind utilities.
    --}}
    <style>
        /* Back link in the page header, same look as on the ticket detail pages. */
        .page-back { align-self: flex-start; display: inline-flex; align-items: center; gap: 8px; font-size: 14px; font-weight: 600; color: #6b7280; text-decoration: none; transition: color .15s; }
        .page-back:hover { color: #374151; }

        #redeem-page { max-width: 64rem; margin: 0 auto; padding: 24px 16px 48px; }
        #redeem-page .alert-err { display: flex; align-items: flex-start; gap: 10px; margin-bottom: 20px; padding: 12px 16px; border-radius: 0.75rem; background: #fde2dc; color: #9a3412; font-size: 13px; font-weight: 600; }
        #redeem-page .alert-ok { display: flex; align-items: center; gap: 10px; margin-bottom: 20px; padding: 12px 16px; border-radius: 0.75rem; background: #dcfce7; color: #16803d; font-size: 13px; font-weight: 600; }

        #redeem-page .stats { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 16px; }
        #redeem-page .card { background: #fff; border: 1px solid #e5e7eb; border-radius: 0.75rem; padding: 20px 24px; box-shadow: 0 1px 2px rgba(20,20,19,0.04); }
        #redeem-page .stat-label { font-size: 12px; font-weight: 600; color: #9a9d8f; }
        #redeem-page .stat-value { margin-top: 4px; font-size: 28px; line-height: 1.2; font-weight: 700; color: #111827; }
        #redeem-page .stat-value small { font-size: 13px; font-weight: 600; color: #9a9d8f; margin-left: 4px; }
        #redeem-page .stat-note { margin-top: 2px; font-size: 12px; color: #9a9d8f; }
        #redeem-page .stat-balance { background: #4f46e5; border-color: #4f46e5; }
        #redeem-page .stat-balance .stat-label, #redeem-page .stat-balance .stat-note, #redeem-page .stat-balance .stat-value small { color: #c7d2fe; }
        #redeem-page .stat-balance .stat-value { color: #fff; }
        #redeem-page .stat-negative .stat-value { color: #fecaca; }

        #redeem-page .rewards { margin-top: 20px; display: grid; grid-template-columns: 1fr auto; gap: 24px; align-items: center; }
        #redeem-page .card-title { font-size: 13px; font-weight: 700; color: #14171a; text-transform: uppercase; letter-spacing: 0.04em; margin: 0 0 14px; }
        #redeem-page .tiers { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 12px; }
        #redeem-page .tier { border: 1px solid #e5e7eb; border-radius: 0.6rem; padding: 12px 14px; background: #fafafa; }
        #redeem-page .tier.ok { border-color: #bbf7d0; background: #f0fdf4; }
        #redeem-page .tier-pts { font-size: 13px; font-weight: 700; color: #111827; }
        #redeem-page .tier-val { font-size: 12px; color: #6b7280; }
        #redeem-page .tier-state { margin-top: 6px; font-size: 11px; font-weight: 700; color: #9ca3af; }
        #redeem-page .tier.ok .tier-state { color: #16803d; }
        #redeem-page .redeem-btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; padding: 12px 22px; border: 0; border-radius: 0.5rem; background: #16a34a; color: #fff; font-size: 14px; font-weight: 700; cursor: pointer; white-space: nowrap; }
        #redeem-page .redeem-btn:hover { background: #15803d; }

        #redeem-page .tabs { display: flex; gap: 4px; margin: 24px 0 16px; padding: 4px; background: #eceef1; border-radius: 0.75rem; }
        #redeem-page .tab { flex: 1; text-align: center; padding: 9px 12px; border-radius: 0.5rem; font-size: 13px; font-weight: 600; color: #6b7280; text-decoration: none; }
        #redeem-page .tab:hover { color: #111827; }
        #redeem-page .tab.is-active { background: #fff; color: #4338ca; box-shadow: 0 1px 2px rgba(20,20,19,0.1); }

        #redeem-page .table-wrap { background: #fff; border: 1px solid #e5e7eb; border-radius: 0.75rem; overflow-x: auto; box-shadow: 0 1px 2px rgba(20,20,19,0.04); }
        #redeem-page table { width: 100%; border-collapse: collapse; font-size: 13px; }
        #redeem-page thead { background: #f9fafb; }
        #redeem-page th { text-align: left; padding: 14px 16px; font-size: 11px; font-weight: 600; letter-spacing: 0.05em; text-transform: uppercase; color: #6b7280; white-space: nowrap; user-select: none; }
        #redeem-page th a { display: inline-flex; align-items: center; gap: 5px; color: #6b7280; text-decoration: none; }
        #redeem-page th a:hover { color: #1f2937; }
        #redeem-page th.is-sorted a { color: #4338ca; }
        #redeem-page .sort-icon { opacity: 0.45; }
        #redeem-page th.is-sorted .sort-icon { opacity: 1; }
        #redeem-page td { padding: 14px 16px; color: #374151; border-top: 1px solid #f0f1f3; vertical-align: middle; }
        #redeem-page tbody tr:hover { background: #f8f9fc; }
        #redeem-page .strong { font-weight: 700; color: #111827; font-size: 15px; }
        #redeem-page .muted { color: #6b7280; font-size: 12px; }
        #redeem-page .pill { display: inline-flex; align-items: center; gap: 6px; border-radius: 9999px; padding: 4px 12px; font-size: 12px; font-weight: 700; }
        #redeem-page .pill i { width: 6px; height: 6px; border-radius: 9999px; display: inline-block; }
        #redeem-page .pill-pending { background: #fef3c7; color: #92400e; } #redeem-page .pill-pending i { background: #f59e0b; }

        #redeem-page .empty { text-align: center; padding: 48px 24px !important; color: #6b7280; }
        #redeem-page .empty-icon { width: 44px; height: 44px; margin: 0 auto 12px; border-radius: 12px; background: #eef2ff; color: #4f46e5; display: flex; align-items: center; justify-content: center; }
        #redeem-page .empty-title { font-size: 15px; font-weight: 700; color: #111827; }
        #redeem-page .empty-text { font-size: 13px; margin-top: 2px; }
        #redeem-page .pager { margin-top: 20px; }

        @media (max-width: 800px) {
            #redeem-page .stats { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            #redeem-page .rewards { grid-template-columns: 1fr; }
            #redeem-page .redeem-btn { width: 100%; }
        }
        @media (max-width: 520px) {
            #redeem-page .card { padding: 16px; }
            #redeem-page .tiers { grid-template-columns: 1fr; }
            #redeem-page .stat-value { font-size: 24px; }
            #redeem-page th, #redeem-page td { padding: 12px 8px; }
            #redeem-page th:first-child, #redeem-page td:first-child { padding-left: 14px; }
            #redeem-page .pill { padding: 3px 9px; }
        }
    </style>

    <div id="redeem-page">
        @if (session('success'))
            <div class="alert-ok">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5" /></svg>
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert-err" role="alert">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0; margin-top: 1px;"><circle cx="12" cy="12" r="10" /><path d="M12 8v4" /><path d="M12 16h.01" /></svg>
                <div>
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Points summary --}}
        <div class="stats">
            <div class="card">
                <div class="stat-label">Total Points Earned</div>
                <div class="stat-value">{{ $point_total ?? 0 }}<small>pts</small></div>
                <div class="stat-note">1 point per closed ticket</div>
            </div>
            <div class="card">
                <div class="stat-label">Points in Progress</div>
                <div class="stat-value">{{ $point_floating ?? 0 }}<small>pts</small></div>
                <div class="stat-note">Waiting for approval</div>
            </div>
            <div class="card">
                <div class="stat-label">Points Redeemed</div>
                <div class="stat-value">{{ $point_redeem ?? 0 }}<small>pts</small></div>
                <div class="stat-note">Already approved</div>
            </div>
            <div class="card stat-balance {{ $balance < 0 ? 'stat-negative' : '' }}">
                <div class="stat-label">Available Balance</div>
                <div class="stat-value">{{ $balance }}<small>pts</small></div>
                <div class="stat-note">You can redeem up to this</div>
            </div>
        </div>

        {{-- Rewards + request button --}}
        <div class="card rewards">
            <div>
                <h3 class="card-title">Redeemable rewards</h3>
                <div class="tiers">
                    @foreach ($rewards as $pts => $voucher)
                        <div class="tier {{ $balance >= $pts ? 'ok' : '' }}">
                            <div class="tier-pts">{{ $pts }} points</div>
                            <div class="tier-val">{{ $voucher }} Coupon/Voucher</div>
                            <div class="tier-state">
                                {{ $balance >= $pts ? 'You can redeem this' : 'Need ' . ($pts - max($balance, 0)) . ' more' }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <button type="button" class="redeem-btn" onclick="handleClickActionButton(true)">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 12v9H4v-9" /><path d="M2 7h20v5H2z" /><path d="M12 21V7" /><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z" /><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z" /></svg>
                Submit Redeem Request
            </button>
        </div>

        {{-- Status tabs --}}
        <div class="tabs">
            @foreach ($tabs as $item)
                {{-- "Approved On" only exists on the Approved tab, so fall back to Requested On elsewhere. --}}
                @php $tabSort = $sortField === 'respond_at' && $item->name !== 'Approved' ? 'created_at' : $sortField; @endphp
                <a href="{{ $item->link }}?{{ http_build_query(['sort' => $tabSort, 'direction' => $sortDirection]) }}"
                    class="tab {{ $item->isActive ? 'is-active' : '' }}">
                    {{ $item->name }}
                </a>
            @endforeach
        </div>

        {{-- Requests --}}
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
                        @unless ($isApprovedTab)
                            <th>Status</th>
                        @endunless
                    </tr>
                </thead>
                <tbody>
                    @forelse ($redeems as $item)
                        <tr>
                            <td>
                                <span class="strong">{{ $item->points }}</span> <span class="muted">points</span>
                                @if (isset($rewards[(int) $item->points]))
                                    <div class="muted">{{ $rewards[(int) $item->points] }} Coupon/Voucher</div>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y, g:i A') }}</td>
                            @if ($isApprovedTab)
                                <td>{{ $item->respond_at ? \Carbon\Carbon::parse($item->respond_at)->format('d/m/Y, g:i A') : '—' }}</td>
                            @else
                                <td><span class="pill pill-pending"><i></i>Pending</span></td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($columns) + ($isApprovedTab ? 0 : 1) }}" class="empty">
                                <div class="empty-icon">
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 12v9H4v-9" /><path d="M2 7h20v5H2z" /><path d="M12 21V7" /></svg>
                                </div>
                                <div class="empty-title">No {{ $isApprovedTab ? 'approved' : 'pending' }} requests</div>
                                <div class="empty-text">{{ $isApprovedTab ? 'Approved redemptions will appear here.' : 'Submit a redeem request to see it here.' }}</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pager">
            {{ $redeems->links() }}
        </div>
    </div>

    @component('submitter.Confirm', [
        'staff_id' => $staff_id,
        'submitter' => $submitter,
        'point_total' => $point_total,
        'point_floating' => $point_floating,
        'point_redeem' => $point_redeem,
        'point_balance' => $point_balance,
    ])
    @endcomponent
</x-layouts.guest2-layout>
