<x-app-layout>
    <x-slot name="header">
        <div class="grid md:grid-cols-2 items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    State & Branch List
                </h2>
            </div>
            <div class="text-right">
                <a class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-3 rounded inline-block"
                    href="{{ route('StateBranch.create') }}">New Branch</a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <!-- Centered and narrower container -->
        <div class="w-full max-w-3xl mx-auto px-4">
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
            <div class="accordion-container">
                @forelse ($states as $state)
                    <div class="accordion-item border rounded-lg shadow-sm mb-4">
                        <!-- Accordion Header -->
                        <button
                            class="accordion-header w-full text-left px-4 py-2 bg-white hover:bg-gray-100 font-semibold text-gray-800 border-b">
                            {{ $state->name }}
                        </button>

                        <!-- Accordion Content -->
                        <div class="accordion-content hidden px-4 py-2 bg-white">
                            @forelse ($state->branch as $branch)
                                <a href="{{ route('branch_detail', ['state_id' => $branch->state_id, 'branch_id' => $branch->id]) }}"
                                    class="block mb-2">
                                    <div
                                        class="flex justify-between items-center p-3 border rounded-md bg-white hover:bg-blue-50 transition-colors shadow-sm">
                                        <div class="text-sm text-gray-800 font-medium">{{ $branch->name }}</div>
                                        <div>
                                            <span
                                                class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                                                {{ $branch->is_enabled ? 'bg-green-100 text-green-800 border border-green-300' : 'bg-red-100 text-red-800 border border-red-300' }}">
                                                {{ $branch->is_enabled ? 'Active' : 'Not Active' }}
                                            </span>
                                        </div>
                                    </div>
                                </a>
                            @empty
                                <div class="text-gray-500 text-sm">No branches available.</div>
                            @endforelse
                        </div>
                    </div>
                @empty
                    <div class="text-center text-gray-500 py-4">
                        No states available.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Simple Accordion Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const headers = document.querySelectorAll('.accordion-header');

            headers.forEach(header => {
                header.addEventListener('click', function() {
                    const content = this.nextElementSibling;
                    content.classList.toggle('hidden');
                });
            });
        });
    </script>
</x-app-layout>
