<x-layouts.guest2-layout>
    <x-slot name="header">
        <div class="grid md:grid-cols-2">
            <div class="flex justify-start">
                <div class="inline-block mr-20">
                    <a
                        href="{{ route('SearchTicketResult', ['status' => $ticket->status]) }}{{ $ticket->staff_id ? '?staff_id=' . $ticket->staff_id : '' }}">Back</a>
                </div>
                <div>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        Observation #{{ $ticket->id }} <span
                            class="inline-block @if ($ticket->status == 'Open') bg-yellow-200 @elseif($ticket->status == 'Declined') bg-red-200 @else bg-green-200 @endif rounded-full py-1 px-3">{{ $ticket->status }}</span>
                    </h2>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
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

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="grid md:grid-cols-3 gap-x-5">
                        {{-- Left --}}
                        <div>
                            <div class="text-gray-600">Before Action Taken</div>
                            <div>
                                @component('components.Carousel', [
                                    'attachments' => $ticket->attachment->where('level', 1),
                                    'ticket' => $ticket,
                                    'approverLevel' => 0,
                                ])
                                @endcomponent
                            </div>
                            <div class="text-gray-600 mt-4">Corrective Action Taken by Submitter</div>
                            <div>
                                @if ($ticket->attachment->where('level', 2)->count() > 0)
                                    @component('components.Carousel', [
                                        'attachments' => $ticket->attachment->where('level', 2),
                                        'ticket' => $ticket,
                                        'approverLevel' => 0,
                                    ])
                                    @endcomponent
                                @else
                                @endif
                            </div>
                        </div>

                        {{-- Right --}}
                        <div class="md:col-span-2">
                            <div class="flex flex-col gap-y-8">
                                <div class="grid md:grid-cols-2 gap-x-3">
                                    <div>
                                        <div class="text-gray-600">Ticket ID</div>
                                        <div><strong>{{ $ticket->ticket_id }}</strong></div>
                                    </div>
                                    <div>
                                        <div class="text-gray-600">Action Dateline</div>
                                        <div class="text-red"><strong>{{ \Carbon\Carbon::parse($ticket->dateline)->format('d/m/Y') }}</strong></div>
                                    </div>
                                </div>
                                <div class="grid md:grid-cols-2 gap-x-3">
                                    <div>
                                        <div class="text-gray-600">Reported by</div>
                                        <div><strong>{{ $ticket->name }}</strong></div>
                                        <div><strong>{{ $ticket->email }}</strong></div>
                                        <div>{{ $ticket->phone_number }}</div>
                                        <div>{{ $ticket->staff_id }}</div>
                                        <div>{{ $ticket->plant->name }}</div>
                                        @if ($ticket->department_id != 0)
                                            <div>{{ $ticket->department->name }}</div>
                                            @if ($ticket->sub_department_id != 0)
                                                <div>{{ $ticket->sub_department->name }}</div>
                                            @endif
                                        @else
                                            <div>{{ $ticket->department_other }}</div>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="text-gray-600">Reported on</div>
                                        <div>
                                            {{ \Carbon\Carbon::parse($ticket->ticket_created_at)->format('d/m/Y, g:i A') }}
                                        </div>
                                    </div>
                                </div>

                                <div class="grid md:grid-cols-2 gap-x-3">
                                    <div>
                                        <div class="text-gray-600">Affected Areas</div>
                                        <div>{{ $ticket->affected_area }}</div>
                                        <div class="text-gray-600 mt-2">Plant Involved</div>
                                        <div>{{ $ticket->plant->name }}</div>
                                        <div class="text-gray-600 mt-2">Department Responsible</div>
                                        @if ($ticket->dept_res_id != 0)
                                            <div>{{ $ticket->dep_responsible->name }}</div>
                                            @if ($ticket->sub_dept_res_id != 0)
                                                <div>{{ $ticket->sub_dep_responsible->name }}</div>
                                            @endif
                                        @else
                                            <div>{{ $ticket->dept_res_other }}</div>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="text-gray-600">HOP Involved</div>
                                        <ul class="list-disc">
                                            @if ($ticket->plant_involve)
                                                <li>
                                                    <div>{{ $ticket->plant_involve->head_plant->name }}</div>
                                                    <div>{{ $ticket->plant_involve->head_plant->email }}</div>
                                                </li>
                                            @else
                                                <div>No item</div>
                                            @endif
                                        </ul>
                                        <div class="text-gray-600 mt-2">Head of Department Responsible</div>
                                        <ul class="list-disc">
                                            @if ($ticket->sub_dept_res_id == 0)
                                                <li>
                                                    <div>
                                                        {{ $ticket->dep_responsible->head_department != null ? $ticket->dep_responsible->head_department->name : 'No item' }}
                                                    </div>
                                                    <div>
                                                        {{ $ticket->dep_responsible->head_department != null ? $ticket->dep_responsible->head_department->email : 'No item' }}
                                                    </div>
                                                </li>
                                            @elseif ($ticket->sub_dept_res_id != 0)
                                                <li>
                                                    <div>
                                                        {{ $ticket->sub_dep_responsible->head_subdepartment != null ? $ticket->sub_dep_responsible->head_subdepartment->name : 'No item' }}
                                                    </div>
                                                    <div>
                                                        {{ $ticket->sub_dep_responsible->head_subdepartment != null ? $ticket->sub_dep_responsible->head_subdepartment->email : 'No item' }}
                                                    </div>
                                                </li>
                                            @else
                                                <div>No item</div>
                                            @endif
                                        </ul>
                                        <div class="text-gray-600 mt-2">GM Responsible</div>
                                        <ul class="list-disc">
                                            @if ($ticket->gm_responsible)
                                                <li>
                                                    <div>{{ $ticket->gm_responsible->name }}</div>
                                                    <div>{{ $ticket->gm_responsible->email }}</div>
                                                </li>
                                            @else
                                                <div>No item</div>
                                            @endif
                                        </ul>
                                    </div>
                                </div>

                                <div class="grid md:grid-cols-2">
                                    <div>
                                        <div class="text-gray-600">Description</div>
                                        <div>{{ $ticket->description }}</div>
                                    </div>
                                    <div>
                                        <div class="text-gray-600">Action Taken</div>
                                        <div>{{ $ticket->action_taken }}</div>
                                    </div>
                                </div>

                                <div class="grid md:grid-cols-2">
                                    <div>
                                        @if ($ticket->ucua_id == 'unsafe_condition')
                                            <div class="text-gray-600">Unsafe Conditions:</div>
                                        @elseif ($ticket->ucua_id == 'unsafe_act')
                                            <div class="text-gray-600">Unsafe Acts:</div>
                                        @endif
                                        @if ($ticket->ucua_type != 0)
                                            <div>{{ $ticket->unsafe_cond_act->name }}</div>
                                        @else
                                            <div>{{ $ticket->ucua_other }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pb-3 pt-8">
                <h4 class="text-xl">After Action Taken</h4>
            </div>
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
</x-layouts.guest2-layout>
