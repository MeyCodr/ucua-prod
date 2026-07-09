<x-app-layout>
    <x-slot name="header">
        <div class="grid md:grid-cols-2">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Tickets
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="pt-2 pb-1">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-3 bg-white rounded divide-x divide-grey-500 shadow hover:shadow-md">
                @forelse ($tabs as $item)
                    <a href="{{ $item->link }}"
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
                    <a href="{{ route('ShowSelectedTicket', ['category' => $category,'ticketId' => $item->id]) }}">
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
</x-app-layout>
