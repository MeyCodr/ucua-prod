<x-app-layout>
    <x-slot name="header">
        <div class="grid md:grid-cols-2">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $pageTitle }}
                </h2>
            </div>

            <div class="hidden sm:flex flex-row-reverse sm:items-center sm:ml-6">
                <div class="ml-3 relative">
                    <x-dropdown align="right">
                        <x-slot name="trigger">
                            <button
                                class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                                <div>
                                    Menu
                                </div>

                                <div class="ml-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">

                            <x-dropdown-link :href="route('Division.create')" onclick="">
                                New Division
                            </x-dropdown-link>
                        </x-slot>
                    </x-dropdown>
                </div>

            </div>
        </div>
    </x-slot>

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

    {{-- Search User --}}
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col gap-y-1.5">
                <form action="">
                    <div class="text-right">
                        <input type="text" name="name" class=" br-5 border-gray-300 rounded-md"
                            style="height: 30px;" placeholder="search division name...">
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded">Search
                            division</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col gap-y-1.5">
                @forelse ($divisions as $item)
                    <a href="{{ route('Division.show', ['Division' => $item->id]) }}">
                        <div
                            class="grid md:grid-cols-4 bg-white rounded p-3 shadow hover:shadow-md hover:bg-gray-200 text-black">
                            <div>
                                <div class="font-semibold">ID #{{ $item->id }}</div>
                            </div>
                            <div>
                                <div class="text-gray-500">Name</div>
                                <div>{{ $item->name }}</div>
                            </div>
                            <div>
                                <div class="text-gray-500">Head of Division</div>
                                @if ($item->head_div)
                                    <div>{{ $item->head_div->name }}</div>
                                @else
                                    <div>No Head</div>
                                @endif
                            </div>
                            <div>
                                <div class="text-gray-500">Registered on</div>
                                <div>{{ date('d/m/Y h:i A', strtotime($item->created_at)) }}</div>
                            </div>
                        </div>
                    </a>

                @empty
                    <div>No items</div>
                @endforelse

                <div>
                    {{ $divisions->appends(request()->except('page'))->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
