<x-app-layout>
    <x-slot name="header">
        <div class="grid md:grid-cols-2">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Export Tickets
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @php
                $activeFilterCount = collect($filters)->filter(fn($value) => filled($value))->count();
                $inputClasses = 'block w-full rounded-md border-gray-300 shadow-sm text-sm text-gray-700 focus:border-gray-500 focus:ring-gray-500';
                $labelClasses = 'block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5';
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
                @media (min-width: 768px) {
                    .md\:grid-cols-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
                    .md\:col-span-2 { grid-column: span 2 / span 2; }
                }
            </style>

            <div class="bg-white rounded-lg shadow-sm border border-gray-100 mb-6 mt-4">
                <div class="px-5 py-3.5 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 01.8 1.6l-4.8 6.4V17a1 1 0 01-1.447.894l-2-1A1 1 0 018 16v-6l-4.8-6.4A1 1 0 013 3z" clip-rule="evenodd" />
                        </svg>
                        Filter
                    </h3>
                    <span class="text-xs text-gray-400">
                        {{ number_format($matchCount) }} {{ Str::plural('ticket', $matchCount) }} will be exported
                        @if ($activeFilterCount > 0)
                            &middot; {{ $activeFilterCount }} {{ Str::plural('filter', $activeFilterCount) }} applied
                        @endif
                    </span>
                </div>

                <form method="GET" action="{{ route('ShowExportPage') }}" class="p-5">
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
                            <label for="site_id" class="{{ $labelClasses }}">Plant</label>
                            <select id="site_id" name="site_id" class="{{ $inputClasses }}">
                                <option value="">All plants</option>
                                @foreach ($plants as $plant)
                                    <option value="{{ $plant->id }}"
                                        {{ (string) ($filters['site_id'] ?? '') === (string) $plant->id ? 'selected' : '' }}>
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
                            <a href="{{ route('ShowExportPage') }}"
                                class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-800 transition ease-in-out duration-150">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" clip-rule="evenodd" />
                                </svg>
                                Reset filters
                            </a>
                        @endif

                        <button type="submit"
                            class="text-sm text-gray-600 hover:text-gray-900 font-medium transition ease-in-out duration-150">
                            Update preview
                        </button>

                        <a href="{{ route('DownloadTicketsExport', $filters) }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 transition ease-in-out duration-150">
                            Download Excel (.xlsx)
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
