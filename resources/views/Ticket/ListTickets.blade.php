<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-indigo-50 text-indigo-600 flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="9" y="3" width="6" height="4" rx="1" />
                    <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2" />
                    <path d="M9 12h6" /><path d="M9 16h6" />
                </svg>
            </div>
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-gray-900 leading-tight">
                    Tickets
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    @if ($category === 'Pending')
                        Review and action tickets awaiting your approval.
                    @elseif ($category === 'Verified' || $category === 'Completed')
                        Tickets you have already {{ strtolower($category) }}.
                    @else
                        Tickets that have been declined.
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
        .w-8 { width: 2rem; }
        .h-8 { height: 2rem; }
        .w-9 { width: 2.25rem; }
        .w-1\.5 { width: 0.375rem; }
        .h-1\.5 { height: 0.375rem; }
        .text-gray-300 { color: #d1d5db; }
        .gap-3 { gap: 0.75rem; }
        .divide-y > :not([hidden]) ~ :not([hidden]) { border-top-width: 1px; }
        .divide-gray-100 > :not([hidden]) ~ :not([hidden]) { border-color: #f1f3f5; }
        .bg-indigo-600 { background-color: #4f46e5; }
        .border-indigo-600 { border-color: #4f46e5; }
        .hover\:bg-indigo-700:hover { background-color: #4338ca; }
        .hover\:text-gray-700:hover { color: #374151; }

        #tickets-tabs .tab-pill { border: 1px solid #d1d5db; }
        #tickets-tabs .tab-pill.active { border-color: #4f46e5; }

        #tickets-table thead th { border-bottom: 1px solid #e5e7eb; }
        #tickets-table tbody tr:nth-child(even) { background-color: #fafafa; }
        #tickets-table tbody tr:hover { background-color: #f3f4f6; }
        #tickets-table .sort-link { color: #6b7280; text-decoration: none; }
        #tickets-table .sort-link:hover { color: #1f2937; }
        #tickets-table .sort-icon { opacity: 0.45; }
        #tickets-table .sort-icon.active { opacity: 1; }
    </style>

    <div class="pt-2 pb-1">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div id="tickets-tabs" class="flex flex-wrap gap-2">
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
                $sortLink = fn (string $field) => route('ShowListTickets', [
                    'category' => $category,
                    'sort' => $field,
                    'direction' => $sortField === $field && $sortDirection === 'asc' ? 'desc' : 'asc',
                ]);
                $columns = [
                    'id' => 'Observation',
                    'ticket_id' => 'Ticket ID',
                    'name' => 'Reported By',
                    'department' => 'Department',
                    'plant' => 'Plant',
                ];
                $initials = function (?string $name) {
                    $parts = array_filter(preg_split('/\s+/', trim((string) $name)));
                    $letters = array_map(fn ($p) => mb_substr($p, 0, 1), array_slice($parts, 0, 2));
                    return mb_strtoupper(implode('', $letters)) ?: '—';
                };
            @endphp

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-x-auto">
                <table id="tickets-table" class="min-w-full table-auto border-collapse text-xs">
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
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($tickets as $item)
                            <tr class="cursor-pointer"
                                onclick="window.location='{{ route('ShowSelectedTicket', ['category' => $category, 'ticketId' => $item->id]) }}'">
                                <td class="px-4 py-4 whitespace-nowrap text-gray-700">#{{ $item->id }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-gray-700">#{{ $item->ticket_id }}</td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-3">
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-indigo-50 text-indigo-700 text-xs font-semibold flex-shrink-0">
                                            {{ $initials($item->name) }}
                                        </span>
                                        <span class="text-gray-800 font-medium">{{ $item->name }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-gray-700">{{ $item->department->name ?? '—' }}</td>
                                <td class="px-4 py-4 text-left text-gray-700">{{ $item->plant_involve?->name ?? '—' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-4 py-3 text-gray-500" colspan="5">No items</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                @if ($tickets->total() > 0)
                    <div class="flex items-center justify-between px-4 py-3 border-t border-gray-200">
                        <p class="text-sm text-gray-500">
                            Showing <span class="font-semibold text-gray-700">{{ $tickets->firstItem() }}–{{ $tickets->lastItem() }}</span>
                            of <span class="font-semibold text-gray-700">{{ $tickets->total() }}</span> tickets
                        </p>
                        <div>
                            {{ $tickets->links('pagination.ucua-pills') }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
