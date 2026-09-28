<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-indigo-50 text-indigo-600 flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 21h18" /><path d="M5 21V7l7-4 7 4v14" /><path d="M9 9h1" /><path d="M9 13h1" /><path d="M14 9h1" /><path d="M14 13h1" /><path d="M10 21v-4h4v4" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-bold tracking-tight text-gray-900 leading-tight">
                        {{ $pageTitle }}
                    </h2>
                    <p class="mt-1 text-sm text-gray-500">
                        Manage organizational divisions and their heads.
                    </p>
                </div>
            </div>

            <a href="{{ route('Division.create') }}"
                class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700 transition duration-150 ease-in-out flex-shrink-0">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 5v14" /><path d="M5 12h14" />
                </svg>
                New Division
            </a>
        </div>
    </x-slot>

    {{--
        The compiled public/css/app.css on this install predates this markup and can't
        currently be rebuilt (Laravel Mix's toolchain doesn't run on this machine), so a
        handful of Tailwind utilities below were never generated. These rules backfill
        exactly those classes with their standard Tailwind values, plus the one-off
        styling (zebra rows) that isn't a plain utility class.
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
        .gap-2 { gap: 0.5rem; }
        .gap-3 { gap: 0.75rem; }
        .text-gray-300 { color: #d1d5db; }
        .divide-y > :not([hidden]) ~ :not([hidden]) { border-top-width: 1px; }
        .divide-gray-100 > :not([hidden]) ~ :not([hidden]) { border-color: #f1f3f5; }
        .bg-indigo-600 { background-color: #4f46e5; }
        .hover\:bg-indigo-700:hover { background-color: #4338ca; }
        .hover\:text-gray-700:hover { color: #374151; }

        #divisions-table thead th { border-bottom: 1px solid #e5e7eb; }
        #divisions-table tbody tr:nth-child(even) { background-color: #fafafa; }
        #divisions-table tbody tr:hover { background-color: #f3f4f6; }
        #divisions-table .sort-link { color: #6b7280; text-decoration: none; }
        #divisions-table .sort-link:hover { color: #1f2937; }
        #divisions-table .sort-icon { opacity: 0.45; }
        #divisions-table .sort-icon.active { opacity: 1; }
    </style>

    {{-- Alert message pop up --}}
    @if (session('alertColor'))
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-3">
            @component('components.Alert1')
                @slot('alertColor')
                    {{ session('alertColor') }}
                @endslot
                @slot('message')
                    {{ session('message') }}
                @endslot
            @endcomponent
        </div>
    @endif

    <div class="pt-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form action="" class="flex justify-end gap-2">
                <input type="text" name="name" value="{{ request('name') }}"
                    class="rounded-md border-gray-300 text-sm" style="height: 38px;"
                    placeholder="Search division name...">
                <button type="submit"
                    class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700 transition duration-150 ease-in-out">
                    Search
                </button>
            </form>
        </div>
    </div>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @php
                $sortLink = fn (string $field) => route('Division.index', array_filter([
                    'name' => request('name'),
                    'sort' => $field,
                    'direction' => $sortField === $field && $sortDirection === 'asc' ? 'desc' : 'asc',
                ]));
                $columns = [
                    'id' => 'ID',
                    'name' => 'Name',
                    'head_div' => 'Head of Division',
                    'created_at' => 'Registered on',
                ];
                $initials = function (?string $name) {
                    $parts = array_filter(preg_split('/\s+/', trim((string) $name)));
                    $letters = array_map(fn ($p) => mb_substr($p, 0, 1), array_slice($parts, 0, 2));
                    return mb_strtoupper(implode('', $letters)) ?: '—';
                };
            @endphp

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-x-auto">
                <table id="divisions-table" class="min-w-full table-auto border-collapse text-xs">
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
                        @forelse ($divisions as $item)
                            <tr class="cursor-pointer"
                                onclick="window.location='{{ route('Division.show', ['Division' => $item->id]) }}'">
                                <td class="px-4 py-4 whitespace-nowrap text-gray-700">#{{ $item->id }}</td>
                                <td class="px-4 py-4 text-gray-800 font-medium">{{ $item->name }}</td>
                                <td class="px-4 py-4">
                                    @if ($item->head_div)
                                        <div class="flex items-center gap-3">
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-indigo-50 text-indigo-700 text-xs font-semibold flex-shrink-0">
                                                {{ $initials($item->head_div->name) }}
                                            </span>
                                            <span class="text-gray-800 font-medium">{{ $item->head_div->name }}</span>
                                        </div>
                                    @else
                                        <span class="text-gray-500">No Head</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-gray-700">{{ date('d/m/Y h:i A', strtotime($item->created_at)) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-4 py-3 text-gray-500" colspan="4">No items</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                @if ($divisions->total() > 0)
                    <div class="flex items-center justify-between px-4 py-3 border-t border-gray-200">
                        <p class="text-sm text-gray-500">
                            Showing <span class="font-semibold text-gray-700">{{ $divisions->firstItem() }}–{{ $divisions->lastItem() }}</span>
                            of <span class="font-semibold text-gray-700">{{ $divisions->total() }}</span> divisions
                        </p>
                        <div>
                            {{ $divisions->appends(request()->except('page'))->links('pagination.ucua-pills') }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
