<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3">
            <a href="{{ route('Division.show', ['Division' => $department->division_id]) }}"
                class="inline-flex items-center gap-2 text-sm font-semibold text-gray-500 hover:text-gray-700 transition duration-150 ease-in-out flex-shrink-0" style="align-self: flex-start;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 12H5" /><path d="M12 19l-7-7 7-7" />
                </svg>
                {{ $department->division?->name }}
            </a>

            <div class="flex items-center justify-between gap-4">
                <div>
                    <div class="text-xs font-bold uppercase tracking-wider text-gray-400">Department #{{ $department->id }}</div>
                    <h2 class="text-2xl font-bold tracking-tight text-gray-900 leading-tight mt-0.5">
                        {{ $department->name }}
                    </h2>
                </div>

                <div class="flex items-center gap-3 flex-shrink-0">
                    <button
                        class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-bold text-red-600 hover:bg-red-50 hover:border-red-300 transition duration-150 ease-in-out"
                        onclick="handleClickDelAccButton(true)">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18" /><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" /><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" /></svg>
                        Delete
                    </button>
                    <a href="{{ route('Department.edit', ['div_id' => $department->division_id, 'Department' => $department->id]) }}"
                        class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-bold text-white hover:bg-indigo-700 transition duration-150 ease-in-out">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" /><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" /></svg>
                        Edit
                    </a>
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
        .rounded-2xl { border-radius: 1rem; }
        .w-8 { width: 2rem; }
        .h-8 { height: 2rem; }
        .w-11 { width: 2.75rem; }
        .h-11 { height: 2.75rem; }
        .w-14 { width: 3.5rem; }
        .h-14 { height: 3.5rem; }
        .gap-2 { gap: 0.5rem; }
        .gap-3 { gap: 0.75rem; }
        .gap-5 { gap: 1.25rem; }
        .mb-5 { margin-bottom: 1.25rem; }
        .mb-6 { margin-bottom: 1.5rem; }
        .bg-indigo-50 { background-color: #eef2ff; }
        .text-indigo-600 { color: #4f46e5; }
        .text-indigo-700 { color: #4338ca; }
        .bg-indigo-600 { background-color: #4f46e5; }
        .hover\:bg-indigo-700:hover { background-color: #4338ca; }
        .hover\:bg-red-50:hover { background-color: #fef2f2; }
        .hover\:border-red-300:hover { border-color: #fca5a5; }
        .bg-red-50 { background-color: #fef2f2; }
        .text-red-600 { color: #dc2626; }
        .bg-red-600 { background-color: #dc2626; }
        .hover\:bg-red-700:hover { background-color: #b91c1c; }
        .py-1\.5 { padding-top: 0.375rem; padding-bottom: 0.375rem; }
        .overflow-x-auto { overflow-x: auto; }
        .min-w-full { min-width: 100%; }
        .table-auto { table-layout: auto; }
        .border-collapse { border-collapse: separate; border-spacing: 0; }
        .whitespace-nowrap { white-space: nowrap; }
        .divide-y > :not([hidden]) ~ :not([hidden]) { border-top-width: 1px; }
        .divide-gray-100 > :not([hidden]) ~ :not([hidden]) { border-color: #f1f3f5; }

        #department-detail .card { background: #fff; border: 1px solid #e5e7eb; border-radius: 0.75rem; padding: 28px; box-shadow: 0 1px 2px rgba(20,20,19,0.04); }
        #department-detail .card-head { display: flex; align-items: center; justify-content: space-between; gap: 10px; margin-bottom: 22px; padding-bottom: 16px; border-bottom: 1px solid #f0efe9; }
        #department-detail .card-head-left { display: flex; align-items: center; gap: 10px; }
        #department-detail .card-icon { width: 32px; height: 32px; border-radius: 10px; background: #eef2ff; color: #4f46e5; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        #department-detail .card-title { margin: 0; font-size: 13px; font-weight: 700; color: #14171a; text-transform: uppercase; letter-spacing: 0.04em; }
        #department-detail .field-label { font-size: 12px; color: #9a9d8f; font-weight: 600; }
        #dept-sub-table thead th, #dept-plant-table thead th { border-bottom: 1px solid #e5e7eb; }
        #dept-sub-table tbody tr:nth-child(even), #dept-plant-table tbody tr:nth-child(even) { background-color: #fafafa; }
        #dept-sub-table tbody tr:hover, #dept-plant-table tbody tr:hover { background-color: #f3f4f6; }
    </style>

    <div id="department-detail" class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Alert message pop up --}}
            @if (session('alertColor'))
                <div class="mb-6">
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
                    <div class="field-label">Department ID</div>
                    <div class="text-sm font-bold text-gray-900 mt-1">#{{ $department->id }}</div>
                </div>
                <div class="card">
                    <div class="field-label">Short Name</div>
                    <div class="text-sm font-bold text-gray-900 mt-1">{{ $department->short_name ?: '—' }}</div>
                </div>
                <div class="card">
                    <div class="field-label">Division</div>
                    <div class="text-sm font-bold text-gray-900 mt-1">{{ $department->division?->name }}</div>
                </div>
            </div>

            {{-- General --}}
            <div class="card mb-5">
                <div class="card-head">
                    <div class="card-head-left">
                        <div class="card-icon">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" /><path d="M9 3v18" /></svg>
                        </div>
                        <h3 class="card-title">General</h3>
                    </div>
                </div>

                <div class="grid md:grid-cols-3 gap-5">
                    <div>
                        <div class="field-label mb-1">Name</div>
                        <div class="text-sm font-bold text-gray-900">{{ $department->name }}</div>
                    </div>
                    <div>
                        <div class="field-label mb-1">Short Name</div>
                        <div class="text-sm font-bold text-gray-900">{{ $department->short_name ?: '—' }}</div>
                    </div>
                    <div>
                        <div class="field-label mb-2">Head of Department</div>
                        @if ($department->head_department)
                            <div class="flex items-center gap-3">
                                <span class="inline-flex items-center justify-center w-11 h-11 rounded-full bg-indigo-50 text-indigo-700 text-xs font-bold flex-shrink-0">
                                    {{ $initials($department->head_department->name) }}
                                </span>
                                <div>
                                    <div class="text-xs font-bold text-gray-900">{{ $department->head_department->name }}</div>
                                    <div class="text-xs text-gray-500 mt-0.5">{{ $department->head_department->email }}</div>
                                </div>
                            </div>
                        @else
                            <span class="text-xs text-gray-500 italic">Not available</span>
                        @endif
                    </div>
                </div>
            </div>

            @if ($department->have_sub_department > 0)
                <div class="card mb-5">
                    <div class="card-head">
                        <div class="card-head-left">
                            <div class="card-icon">
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1.5" /><rect x="14" y="3" width="7" height="7" rx="1.5" /><rect x="3" y="14" width="7" height="7" rx="1.5" /><rect x="14" y="14" width="7" height="7" rx="1.5" /></svg>
                            </div>
                            <h3 class="card-title">Sub Departments</h3>
                        </div>
                        <a href="{{ route('SubDepartment.create', ['div_id' => $department->division_id, 'dept_id' => $department->id]) }}"
                            class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-3 py-1.5 text-xs font-bold text-white hover:bg-indigo-700 transition duration-150 ease-in-out">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14" /><path d="M5 12h14" /></svg>
                            New Sub Department
                        </a>
                    </div>

                    @if ($department->subdepartment->isNotEmpty())
                        <div class="overflow-x-auto">
                            <table id="dept-sub-table" class="min-w-full table-auto border-collapse text-xs">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider px-4 py-3">ID</th>
                                        <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider px-4 py-3">Name</th>
                                        <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider px-4 py-3">Head of Sub Department</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach ($department->subdepartment as $item)
                                        <tr class="cursor-pointer"
                                            onclick="window.location='{{ route('SubDepartment.show', ['div_id' => $department->division_id, 'dept_id' => $department->id, 'SubDepartment' => $item->id]) }}'">
                                            <td class="px-4 py-3 whitespace-nowrap text-gray-700">#{{ $item->id }}</td>
                                            <td class="px-4 py-3 text-gray-800 font-semibold">{{ $item->name }}</td>
                                            <td class="px-4 py-3 text-gray-700">
                                                @if ($item->head_department)
                                                    {{ $item->head_department->name }}
                                                @else
                                                    <span class="text-gray-400">No Head</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-xs text-gray-500 text-center" style="padding: 24px 0;">No sub departments yet.</div>
                    @endif
                </div>
            @endif

            @if ($department->have_plant > 0)
                <div class="card">
                    <div class="card-head">
                        <div class="card-head-left">
                            <div class="card-icon">
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18" /><path d="M5 21V7l7-4 7 4v14" /><path d="M9 9h1" /><path d="M9 13h1" /><path d="M14 9h1" /><path d="M14 13h1" /></svg>
                            </div>
                            <h3 class="card-title">Plants</h3>
                        </div>
                        <a href="{{ route('Plant.create', ['div_id' => $department->division_id, 'dept_id' => $department->id]) }}"
                            class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-3 py-1.5 text-xs font-bold text-white hover:bg-indigo-700 transition duration-150 ease-in-out">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14" /><path d="M5 12h14" /></svg>
                            New Plant
                        </a>
                    </div>

                    @if ($department->plant->isNotEmpty())
                        <div class="overflow-x-auto">
                            <table id="dept-plant-table" class="min-w-full table-auto border-collapse text-xs">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider px-4 py-3">ID</th>
                                        <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider px-4 py-3">Name</th>
                                        <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider px-4 py-3">Head of Plant</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach ($department->plant as $item)
                                        <tr class="cursor-pointer"
                                            onclick="window.location='{{ route('Plant.show', ['div_id' => $department->division_id, 'dept_id' => $department->id, 'Plant' => $item->id]) }}'">
                                            <td class="px-4 py-3 whitespace-nowrap text-gray-700">#{{ $item->id }}</td>
                                            <td class="px-4 py-3 text-gray-800 font-semibold">{{ $item->name }}</td>
                                            <td class="px-4 py-3 text-gray-700">
                                                @if ($item->head_plant)
                                                    {{ $item->head_plant->name }}
                                                @else
                                                    <span class="text-gray-400">No Head</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-xs text-gray-500 text-center" style="padding: 24px 0;">No plants yet.</div>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <script src="{{ asset('jquery/3.4.1/jquery.min.js') }}"></script>
    <script>
        function handleClickDelAccButton(status) {
            if (status) {
                $('.modalDeleteAccLink').show();
            } else {
                $('.modalDeleteAccLink').hide();
            }
        }
    </script>

    <div class="fixed z-10 inset-0 overflow-y-auto modalDeleteAccLink" role="dialog" hidden>
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div
                class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full">
                <form
                    action="{{ route('Department.destroy', ['div_id' => $department->division_id, 'Department' => $department->id]) }}"
                    method="post">
                    @csrf
                    @method('DELETE')
                    <div style="padding: 32px 32px 8px; text-align: left;">
                        <div class="w-14 h-14 rounded-2xl bg-red-50 text-red-600 flex items-center justify-center mb-5">
                            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18" /><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" /><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" /></svg>
                        </div>
                        <h3 class="font-bold text-gray-900" style="font-size: 19px; margin: 0 0 6px;" id="modal-title">
                            Delete Department
                        </h3>
                        <p class="text-gray-500" style="font-size: 13px; line-height: 1.5; margin: 0 0 4px;">
                            You are about to delete <strong>{{ $department->name }}</strong>. This action cannot be undone.
                        </p>
                    </div>
                    <div class="flex items-center justify-end gap-2" style="padding: 16px 32px 28px; border-top: 1px solid #f0efe9;">
                        <button type="button"
                            class="inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white font-semibold text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            style="font-size: 13.5px;"
                            onclick="handleClickDelAccButton(false)">
                            Cancel
                        </button>
                        <button type="submit"
                            class="bg-red-600 hover:bg-red-700 inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 font-bold text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                            style="font-size: 13.5px;">
                            Yes, Delete
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
