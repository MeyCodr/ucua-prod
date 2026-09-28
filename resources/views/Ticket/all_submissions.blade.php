<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-indigo-50 text-indigo-600 flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="7" height="7" rx="1" /><rect x="14" y="3" width="7" height="7" rx="1" />
                    <rect x="14" y="14" width="7" height="7" rx="1" /><rect x="3" y="14" width="7" height="7" rx="1" />
                </svg>
            </div>
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-gray-900 leading-tight">
                    All Tickets
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Browse and filter every ticket submitted across the organization.
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @php
                $activeFilterCount = collect($filters)->filter(fn($value) => filled($value))->count();
                $inputClasses = 'block w-full rounded-md border-gray-300 shadow-sm text-sm text-gray-700 focus:border-gray-500 focus:ring-gray-500';
                $labelClasses = 'block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5';
                $activeFilters = collect($filters)->filter(fn ($value) => filled($value))->all();
                $sortLink = fn (string $field) => route('ShowAllSubmissions', array_merge($activeFilters, [
                    'sort' => $field,
                    'direction' => $sortField === $field && $sortDirection === 'asc' ? 'desc' : 'asc',
                ]));
                $columns = [
                    'id' => 'Observation',
                    'ticket_id' => 'Ticket ID',
                    'name' => 'Reported By',
                    'department' => 'Department',
                    'plant' => 'Plant',
                    'status' => 'Status',
                ];
                $initials = function (?string $name) {
                    $parts = array_filter(preg_split('/\s+/', trim((string) $name)));
                    $letters = array_map(fn ($p) => mb_substr($p, 0, 1), array_slice($parts, 0, 2));
                    return mb_strtoupper(implode('', $letters)) ?: '—';
                };
            @endphp

            {{--
                The compiled public/css/app.css on this install predates this page's markup and
                can't currently be rebuilt (Laravel Mix's toolchain doesn't run on this machine),
                so several Tailwind utilities used below were never generated. These rules
                backfill exactly those classes with their standard Tailwind values.
            --}}
            <style>
                .gap-x-6 { column-gap: 1.5rem; }
                .gap-y-4 { row-gap: 1rem; }
                .gap-2 { gap: 0.5rem; }
                .gap-1\.5 { gap: 0.375rem; }
                .mb-6 { margin-bottom: 1.5rem; }
                .px-5 { padding-left: 1.25rem; padding-right: 1.25rem; }
                .py-3\.5 { padding-top: 0.875rem; padding-bottom: 0.875rem; }
                .pl-9 { padding-left: 2.25rem; }
                .pointer-events-none { pointer-events: none; }
                .inset-y-0 { top: 0; bottom: 0; }
                .shrink-0 { flex-shrink: 0; }
                .h-3\.5 { height: 0.875rem; }
                .w-3\.5 { width: 0.875rem; }
                .tracking-wide { letter-spacing: 0.025em; }
                .tracking-tight { letter-spacing: -0.025em; }
                .text-2xl { font-size: 1.5rem; line-height: 2rem; }
                @media (min-width: 768px) {
                    .md\:grid-cols-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
                    .md\:col-span-2 { grid-column: span 2 / span 2; }
                }

                .overflow-x-auto { overflow-x: auto; }
                .min-w-full { min-width: 100%; }
                .table-auto { table-layout: auto; }
                .border-collapse { border-collapse: separate; border-spacing: 0; }
                .whitespace-nowrap { white-space: nowrap; }
                .tracking-wider { letter-spacing: 0.05em; }
                .select-none { user-select: none; }
                .rounded-xl { border-radius: 0.75rem; }
                .w-8 { width: 2rem; }
                .h-8 { height: 2rem; }
                .w-9 { width: 2.25rem; }
                .px-2\.5 { padding-left: 0.625rem; padding-right: 0.625rem; }
                .py-0\.5 { padding-top: 0.125rem; padding-bottom: 0.125rem; }
                .text-green-700 { color: #15803d; }
                .text-red-700 { color: #b91c1c; }
                .bg-blue-100 { background-color: #dbeafe; }
                .text-blue-700 { color: #1d4ed8; }
                .text-gray-300 { color: #d1d5db; }
                .divide-y > :not([hidden]) ~ :not([hidden]) { border-top-width: 1px; }
                .divide-gray-100 > :not([hidden]) ~ :not([hidden]) { border-color: #f1f3f5; }
                .bg-indigo-600 { background-color: #4f46e5; }
                .hover\:bg-indigo-700:hover { background-color: #4338ca; }
                .hover\:text-gray-700:hover { color: #374151; }

                #submissions-table thead th { border-bottom: 1px solid #e5e7eb; }
                #submissions-table tbody tr:nth-child(even) { background-color: #fafafa; }
                #submissions-table tbody tr:hover { background-color: #f3f4f6; }
                #submissions-table .sort-link { color: #6b7280; text-decoration: none; }
                #submissions-table .sort-link:hover { color: #1f2937; }
                #submissions-table .sort-icon { opacity: 0.45; }
                #submissions-table .sort-icon.active { opacity: 1; }
            </style>

            <div class="bg-white rounded-lg shadow-sm border border-gray-100 mb-6 ">
                <div class="px-5 py-3.5 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 01.8 1.6l-4.8 6.4V17a1 1 0 01-1.447.894l-2-1A1 1 0 018 16v-6l-4.8-6.4A1 1 0 013 3z" clip-rule="evenodd" />
                        </svg>
                        Filter Tickets
                    </h3>
                    <span class="text-xs text-gray-400">
                        {{ number_format($tickets->total()) }} {{ Str::plural('result', $tickets->total()) }}
                        @if ($activeFilterCount > 0)
                            &middot; {{ $activeFilterCount }} {{ Str::plural('filter', $activeFilterCount) }} applied
                        @endif
                    </span>
                </div>

                <form method="GET" action="{{ route('ShowAllSubmissions') }}" class="p-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                        <div class="md:col-span-2">
                            <label for="search" class="{{ $labelClasses }}">Search</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                                <input type="text" id="search" name="search" value="{{ $filters['search'] ?? '' }}"
                                    placeholder="Ticket ID, Staff ID, or name"
                                    class="{{ $inputClasses }} pl-9">
                            </div>
                        </div>

                        <div>
                            <label for="department_id" class="{{ $labelClasses }}">Department</label>
                            <select id="department_id" name="department_id" class="{{ $inputClasses }}">
                                <option value="">All departments</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}"
                                        {{ (string) ($filters['department_id'] ?? '') === (string) $department->id ? 'selected' : '' }}>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="plant_id" class="{{ $labelClasses }}">Plant</label>
                            <select id="plant_id" name="plant_id" class="{{ $inputClasses }}">
                                <option value="">All plants</option>
                                @foreach ($plants as $plant)
                                    <option value="{{ $plant->id }}"
                                        {{ (string) ($filters['plant_id'] ?? '') === (string) $plant->id ? 'selected' : '' }}>
                                        {{ $plant->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="status" class="{{ $labelClasses }}">Status</label>
                            <select id="status" name="status" class="{{ $inputClasses }}">
                                <option value="">All statuses</option>
                                @foreach (['Open', 'Closed', 'Declined'] as $statusOption)
                                    <option value="{{ $statusOption }}"
                                        {{ ($filters['status'] ?? '') === $statusOption ? 'selected' : '' }}>
                                        {{ $statusOption }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <span class="{{ $labelClasses }}">Date range</span>
                            <div class="flex items-center gap-2">
                                <input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}"
                                    aria-label="From date" class="{{ $inputClasses }}">
                                <span class="text-gray-400 text-sm shrink-0">to</span>
                                <input type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}"
                                    aria-label="To date" class="{{ $inputClasses }}">
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-4 mt-5 pt-4 border-t border-gray-100">
                        @if ($activeFilterCount > 0)
                            <a href="{{ route('ShowAllSubmissions') }}"
                                class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-800 transition ease-in-out duration-150">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" clip-rule="evenodd" />
                                </svg>
                                Reset filters
                            </a>
                        @endif
                        <x-button>
                            Apply Filters
                        </x-button>
                    </div>
                </form>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-x-auto">
                <table id="submissions-table" class="min-w-full table-auto border-collapse text-xs">
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
                                onclick="window.location='{{ route('ShowDetail', ['ticketId' => $item->id]) }}'">
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
                                <td class="px-4 py-4 text-gray-700">{{ $item->department?->name ?? $item->department_other ?? '—' }}</td>
                                <td class="px-4 py-4 text-gray-700">{{ $item->plant_involve?->name ?? '—' }}</td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    @if ($item->status === 'Closed')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700">Closed</span>
                                        <div class="mt-1 text-xs text-gray-400">
                                            {{ optional($item->approval->where('approver_level', 2)->first())->respond_at ? \Carbon\Carbon::parse($item->approval->where('approver_level', 2)->first()->respond_at)->format('d/m/Y, g:i A') : 'N/A' }}
                                        </div>
                                    @elseif ($item->status === 'Declined')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700">Declined</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">{{ $item->status }}</span>
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
