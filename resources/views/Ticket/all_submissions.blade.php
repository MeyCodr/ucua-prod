<x-app-layout>
    <x-slot name="header">
        <div class="grid md:grid-cols-2">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    All Tickets
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col gap-y-1.5">
                @forelse ($tickets as $item)
                    <a href="{{ route('ShowDetail', ['ticketId' => $item->id]) }}">
                        <div class="grid md:grid-cols-4 bg-white rounded p-4 shadow hover:shadow-md hover:bg-gray-200">
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
                            <div class="text-right">
                                @if ($item->status == 'Closed')
                                    <div class="inline-block bg-green-200 rounded-full py-1 px-3">
                                        {{ $item->status }}</div>
                                    <div style="text-align:right;">
                                        <div class="datetime">On
                                            {{ \Carbon\Carbon::parse($item->approval->where('approver_level', 2)->first()->respond_at)->format('d/m/Y, g:i A') }}
                                        </div>
                                    </div>
                                @else
                                    <div class="inline-block bg-yellow-200 rounded-full py-1 px-3">
                                        {{ $item->status }}</div>
                                @endif
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
