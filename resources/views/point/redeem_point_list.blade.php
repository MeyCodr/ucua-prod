<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-indigo-50 text-indigo-600 flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="8" r="6" />
                    <path d="M8.21 13.89L7 23l5-3 5 3-1.21-9.12" />
                </svg>
            </div>
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-gray-900 leading-tight">
                    Redemption Requests
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    @if ($status === 'Pending')
                        Review and approve point redemption requests.
                    @else
                        Point redemption requests you have approved.
                    @endif
                </p>
            </div>
        </div>
    </x-slot>

    {{--
        The compiled public/css/app.css on this install predates this markup and can't
        currently be rebuilt (Laravel Mix's toolchain doesn't run on this machine), so a
        handful of Tailwind utilities below were never generated. These rules backfill
        exactly those classes with their standard Tailwind values, plus the one-off
        styling (zebra rows, sort arrows, pill tabs) that isn't a plain utility class.
    --}}
    <style>
        .overflow-x-auto { overflow-x: auto; }
        .min-w-full { min-width: 100%; }
        .table-auto { table-layout: auto; }
        .border-collapse { border-collapse: separate; border-spacing: 0; }
        .whitespace-nowrap { white-space: nowrap; }
        .tracking-wider { letter-spacing: 0.05em; }
        .tracking-tight { letter-spacing: -0.025em; }
        .text-2xl { font-size: 1.5rem; line-height: 2rem; }
        .select-none { user-select: none; }
        .rounded-xl { border-radius: 0.75rem; }
        .w-9 { width: 2.25rem; }
        .px-2\.5 { padding-left: 0.625rem; padding-right: 0.625rem; }
        .py-0\.5 { padding-top: 0.125rem; padding-bottom: 0.125rem; }
        .text-green-700 { color: #15803d; }
        .bg-yellow-100 { background-color: #fef9c3; }
        .text-yellow-800 { color: #854d0e; }
        .text-gray-300 { color: #d1d5db; }
        .divide-y > :not([hidden]) ~ :not([hidden]) { border-top-width: 1px; }
        .divide-gray-100 > :not([hidden]) ~ :not([hidden]) { border-color: #f1f3f5; }
        .bg-indigo-600 { background-color: #4f46e5; }
        .border-indigo-600 { border-color: #4f46e5; }
        .hover\:bg-indigo-700:hover { background-color: #4338ca; }
        .hover\:text-gray-700:hover { color: #374151; }

        #redeem-tabs .tab-pill { border: 1px solid #d1d5db; }
        #redeem-tabs .tab-pill.active { border-color: #4f46e5; }

        #redeem-table thead th { border-bottom: 1px solid #e5e7eb; }
        #redeem-table tbody tr:nth-child(even) { background-color: #fafafa; }
        #redeem-table tbody tr:hover { background-color: #f3f4f6; }
        #redeem-table .sort-link { color: #6b7280; text-decoration: none; }
        #redeem-table .sort-link:hover { color: #1f2937; }
        #redeem-table .sort-icon { opacity: 0.45; }
        #redeem-table .sort-icon.active { opacity: 1; }
    </style>

    <div class="pt-2 pb-1">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div id="redeem-tabs" class="flex flex-wrap gap-2">
                @forelse ($tabs as $item)
                    <a href="{{ $item->link }}"
                        class="tab-pill @if ($item->isActive) active @endif inline-flex items-center rounded-full px-4 py-2 text-sm font-semibold transition ease-in-out duration-150
                            @if ($item->isActive) bg-indigo-600 text-white hover:bg-indigo-700 @else bg-white text-gray-600 hover:bg-gray-50 @endif">
                        {{ $item->name }}
                    </a>
                @empty
                @endforelse
            </div>
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @php
                $sortLink = fn (string $field) => route('admin.redeem.list', [
                    'status' => $status,
                    'sort' => $field,
                    'direction' => $sortField === $field && $sortDirection === 'asc' ? 'desc' : 'asc',
                ]);
                $columns = [
                    'id' => 'ID',
                    'staff_id' => 'Staff ID',
                    'points' => 'Points to Redeem',
                    'created_at' => 'Requested At',
                ];
            @endphp

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-x-auto">
                <table id="redeem-table" class="min-w-full table-auto border-collapse text-xs">
                    <thead class="bg-gray-50">
                        <tr>
                            @foreach ($columns as $field => $label)
                                <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider px-4 py-4 select-none">
                                    <a href="{{ $sortLink($field) }}" class="sort-link inline-flex items-center gap-1">
                                        {{ $label }}
                                        <svg class="sort-icon @if ($sortField === $field) active @endif" width="10" height="10" viewBox="0 0 24 24" fill="none"
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
                                @if ($field === 'staff_id')
                                    <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider px-4 py-4 select-none">Name</th>
                                @endif
                            @endforeach
                            <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider px-4 py-4 select-none">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($redeems as $item)
                            <tr class="cursor-pointer"
                                onclick="window.location='{{ route('admin.redeem.list.staff', ['status' => $status, 'staff_id' => $item->staff_id, 'redeem_id' => $item->id]) }}'">
                                <td class="px-4 py-4 whitespace-nowrap text-gray-700">#{{ $item->id }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-gray-700">#{{ $item->staff_id }}</td>
                                <td class="px-4 py-4 text-gray-800 font-medium">{{ $item->reporter_name ?? '—' }}</td>
                                <td class="px-4 py-4 text-gray-800 font-medium">{{ $item->points }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-gray-700">{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y h:i A') }}</td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    @if ($item->approver_id != null && $item->respond_at != null)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700">Approved</span>
                                        <div class="mt-1 text-xs text-gray-400">{{ \Carbon\Carbon::parse($item->respond_at)->format('d/m/Y, h:i A') }}</div>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">Pending</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-4 py-3 text-gray-500" colspan="6">No items</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                @if ($redeems->total() > 0)
                    <div class="flex items-center justify-between px-4 py-3 border-t border-gray-200">
                        <p class="text-sm text-gray-500">
                            Showing <span class="font-semibold text-gray-700">{{ $redeems->firstItem() }}–{{ $redeems->lastItem() }}</span>
                            of <span class="font-semibold text-gray-700">{{ $redeems->total() }}</span> requests
                        </p>
                        <div>
                            {{ $redeems->links('pagination.ucua-pills') }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
