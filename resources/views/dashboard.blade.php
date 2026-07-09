<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Home
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if ($numTicketsPendingVerify > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-3">
                    <div class="p-6 bg-white border-b border-gray-200 grid grid-cols-2">
                        <div class="">
                            You have {{ $numTicketsPendingVerify }} observations to verify
                        </div>
                        <div class="flex justify-end">
                            <a class="bg-white text-blue-500 hover:bg-blue-100 py-1 px-2 rounded inline-block"
                                href="{{ route('ShowListTickets', ['mode' => 1, 'category' => 1]) }}">View</a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
