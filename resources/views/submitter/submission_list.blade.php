<x-layouts.guest2-layout>
    <x-slot name="header">
        <div class="grid md:grid-cols-2">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Ticket List
                </h2>
            </div>
            <div align="right">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Current Points: {{ $point ?? 0 }}
                </h2>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    <a href="{{ route('redeem.point', ['staff_id' => $staff_id, 'status' => 'Pending']) }}"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Redeem Your Points
                    </a>
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="pt-2 pb-1">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-3 bg-white rounded divide-x divide-grey-500 shadow hover:shadow-md">
                @forelse ($tabs as $item)
                    <a href="{{ $item->link }}{{ $staff_id ? '?staff_id=' . $staff_id : '' }}"
                        class="text-center inline-block bg-white rounded p-4 hover:bg-gray-200 hover:text-black @if ($item->isActive) bg-gray-600 text-white @endif">
                        {{ $item->name }}
                    </a>
                @empty
                @endforelse
            </div>
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col gap-y-1.5">
                @forelse ($tickets as $item)
                    <a href="{{ route('SearchTicketDetail', ['status' => $item->status, 'ticket_id' => $item->id]) }}">
                        <div class="grid md:grid-cols-3 bg-white rounded p-4 shadow hover:shadow-md hover:bg-gray-200">
                            <div>
                                <strong>Observation</strong>
                                <div class="text-lg">#{{ $item->id }}</div>
                                <strong>Ticket ID</strong>
                                <div class="text-lg">#{{ $item->ticket_id }}</div>
                            </div>
                            <div>
                                <div><strong>Reported by</strong></div>
                                <div>{{ $item->name }}</div>
                                <div>{{ $item->email }}</div>
                                <div>{{ $item->phone_number }}</div>
                            </div>
                            <div>
                                <div><strong>Department</strong></div>
                                <div>{{ $item->department->name }}</div>
                                <div><strong>Plant</strong></div>
                                <div>{{ $item->plant->name }}</div>
                            </div>
                        </div>
                    </a>
                @empty
                    <div>No items</div>
                @endforelse
            </div>
            <div>
                {{ $tickets->links() }}
            </div>
        </div>
    </div>
</x-layouts.guest2-layout>
