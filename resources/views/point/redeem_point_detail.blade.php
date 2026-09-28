<x-layouts.guest2-layout>
    <x-slot name="header">
        @php
            $isApproved = $redeem->approver_id != null && $redeem->respond_at != null;
            $statusStyle = $isApproved
                ? ['bg' => '#DCFCE7', 'fg' => '#16803D', 'dot' => '#22A557']
                : ['bg' => '#FEF3C7', 'fg' => '#92400E', 'dot' => '#F59E0B'];
        @endphp
        <div class="flex flex-col gap-3">
            <a href="{{ route('admin.redeem.list', ['status' => $status]) }}"
                class="inline-flex items-center gap-2 text-sm font-semibold text-gray-500 hover:text-gray-700 transition duration-150 ease-in-out flex-shrink-0" style="align-self: flex-start;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 12H5" /><path d="M12 19l-7-7 7-7" />
                </svg>
                {{ $status }}
            </a>

            <div class="flex items-center justify-between gap-4">
                <div>
                    <div class="text-xs font-bold uppercase tracking-wider text-gray-400">Redemption Request</div>
                    <div class="flex items-center gap-3 mt-0.5">
                        <h2 class="text-2xl font-bold tracking-tight text-gray-900 leading-tight">
                            #{{ $redeem->id }}
                        </h2>
                        <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-bold"
                            style="background: {{ $statusStyle['bg'] }}; color: {{ $statusStyle['fg'] }};">
                            <span class="w-1.5 h-1.5 rounded-full" style="background: {{ $statusStyle['dot'] }};"></span>
                            {{ $isApproved ? 'Approved' : 'Pending' }}
                        </span>
                    </div>
                </div>

                @unless ($isApproved)
                    <button
                        class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-bold text-white hover:bg-indigo-700 transition duration-150 ease-in-out flex-shrink-0"
                        onclick="handleClickActionButton(true, 'Approve')">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5" /></svg>
                        Approve
                    </button>
                @endunless
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
        .w-10 { width: 2.5rem; }
        .h-10 { height: 2.5rem; }
        .gap-1\.5 { gap: 0.375rem; }
        .gap-2 { gap: 0.5rem; }
        .gap-3 { gap: 0.75rem; }
        .gap-4 { gap: 1rem; }
        .gap-5 { gap: 1.25rem; }
        .mb-5 { margin-bottom: 1.25rem; }
        .mb-6 { margin-bottom: 1.5rem; }
        .mt-2 { margin-top: 0.5rem; }
        .px-2\.5 { padding-left: 0.625rem; padding-right: 0.625rem; }
        .py-0\.5 { padding-top: 0.125rem; padding-bottom: 0.125rem; }
        .rounded-full { border-radius: 9999px; }
        .bg-indigo-50 { background-color: #eef2ff; }
        .text-indigo-600 { color: #4f46e5; }
        .text-indigo-700 { color: #4338ca; }
        .bg-indigo-600 { background-color: #4f46e5; }
        .hover\:bg-indigo-700:hover { background-color: #4338ca; }

        #redeem-detail .card { background: #fff; border: 1px solid #e5e7eb; border-radius: 0.75rem; padding: 28px; box-shadow: 0 1px 2px rgba(20,20,19,0.04); }
        #redeem-detail .card-head { display: flex; align-items: center; gap: 10px; margin-bottom: 22px; padding-bottom: 16px; border-bottom: 1px solid #f0efe9; }
        #redeem-detail .card-icon { width: 32px; height: 32px; border-radius: 10px; background: #eef2ff; color: #4f46e5; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        #redeem-detail .card-title { margin: 0; font-size: 13px; font-weight: 700; color: #14171a; text-transform: uppercase; letter-spacing: 0.04em; }
        #redeem-detail .field-label { font-size: 12px; color: #9a9d8f; font-weight: 600; }
    </style>

    <div id="redeem-detail" class="py-6">
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
                    <div class="field-label">Staff ID</div>
                    <div class="text-sm font-bold text-gray-900 mt-1">{{ $redeem->staff_id }}</div>
                </div>
                <div class="card">
                    <div class="field-label">Points to Redeem</div>
                    <div class="text-sm font-bold text-gray-900 mt-1">{{ $redeem->points }}</div>
                </div>
                <div class="card">
                    <div class="field-label">Requested On</div>
                    <div class="text-sm font-bold text-gray-900 mt-1">
                        {{ \Carbon\Carbon::parse($redeem->created_at)->format('d/m/Y, g:i A') }}
                    </div>
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-5 items-start">
                {{-- Requested By --}}
                <div class="card">
                    <div class="card-head">
                        <div class="card-icon">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4" /><path d="M4 21v-1a8 8 0 0 1 16 0v1" /></svg>
                        </div>
                        <h3 class="card-title">Requested By</h3>
                    </div>

                    <div class="flex items-center gap-3 mb-5">
                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-indigo-50 text-indigo-700 text-xs font-bold flex-shrink-0">
                            {{ $initials($submitter->name) }}
                        </span>
                        <div>
                            <div class="text-xs font-bold text-gray-900">{{ $submitter->name }}</div>
                            <div class="text-xs text-gray-500 mt-0.5">{{ $submitter->email }}</div>
                        </div>
                    </div>

                    <div class="flex justify-between gap-3">
                        <span class="field-label">Phone</span>
                        <span class="text-xs font-semibold text-gray-800">{{ $submitter->phone_number }}</span>
                    </div>
                    <div class="flex justify-between gap-3 mt-4">
                        <span class="field-label">Staff ID</span>
                        <span class="text-xs font-semibold text-gray-800">{{ $submitter->staff_id }}</span>
                    </div>
                </div>

                {{-- Review Timeline --}}
                <div class="card">
                    <div class="card-head">
                        <div class="card-icon">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9" /><path d="M12 7v5l3 3" /></svg>
                        </div>
                        <h3 class="card-title">Review Timeline</h3>
                    </div>

                    <div class="flex gap-4">
                        <div class="flex items-center justify-center rounded-full flex-shrink-0"
                            style="width: 30px; height: 30px; background: {{ $statusStyle['bg'] }}; color: {{ $statusStyle['fg'] }}; {{ $isApproved ? '' : 'border: 2px solid ' . $statusStyle['fg'] . ';' }}">
                            @if ($isApproved)
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5" /></svg>
                            @else
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="1" /></svg>
                            @endif
                        </div>
                        <div>
                            <div class="field-label" style="text-transform: uppercase; letter-spacing: 0.05em;">SHE Admin PHN</div>

                            <div class="text-xs font-semibold text-gray-800 mt-1">
                                @if ($isApproved)
                                    {{ $redeem->approver?->name }} <span class="text-gray-400 font-normal">&middot; {{ $redeem->approver?->email }}</span>
                                @else
                                    <span class="text-gray-500 font-normal">Awaiting review</span>
                                @endif
                            </div>

                            <div class="flex items-center gap-2 mt-2">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-bold" style="background: {{ $statusStyle['bg'] }}; color: {{ $statusStyle['fg'] }};">
                                    {{ $isApproved ? 'Approved' : 'Pending' }}
                                </span>
                                @if ($isApproved)
                                    <span class="text-xs text-gray-500">On {{ \Carbon\Carbon::parse($redeem->respond_at)->format('d/m/Y, g:i A') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @component('point.Confirm', [
        'status' => $status,
        'redeem' => $redeem,
        'staff_id' => $redeem->staff_id,
        'submitter' => $submitter,
    ])
    @endcomponent
</x-layouts.guest2-layout>
