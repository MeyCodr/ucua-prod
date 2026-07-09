<x-app-layout>
    <x-slot name="header">
        <div class="grid md:grid-cols-2">
            <div class="flex justify-start">
                <div class="inline-block mr-20">
                    <a href="{{ route('Department.show', ['div_id' => $div_id, 'Department' => $dept_id]) }}">Back</a>
                </div>
                <div>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        Plant #{{ $plant->id }}
                    </h2>
                </div>
            </div>
            <div class="text-right">
                <a class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded"
                    href="{{ route('Plant.edit', ['div_id' => $div_id, 'dept_id' => $dept_id, 'Plant' => $plant->id]) }}">Edit</a>
            </div>
        </div>
    </x-slot>

    {{-- Alert message pop up --}}
    @if (session('alertColor'))
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 my-2">
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

    <div class="py-6">
        {{-- Main Info --}}
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-2">
            {{-- General Detail --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="pb-4 font-bold">
                        General
                    </div>
                    <div class="grid md:grid-cols-4 gap-4">
                        <div>
                            <div>
                                Name
                            </div>
                            <div>
                                {{ $plant->name }}
                            </div>
                        </div>

                        <div>
                            <div>
                                Short Name
                            </div>
                            <div>
                                {{ $plant->short_name }}
                            </div>
                        </div>

                        <div>
                            <div>
                                Head of Sub Department
                            </div>
                            <div>
                                @if ($plant->user_head_id)
                                    <ul class="list-disc">
                                        <li>{{ $plant->head_plant->name }}</li>
                                        <li>{{ $plant->head_plant->email }}</li>
                                    </ul>
                                @else
                                    <span class="italic">Not available</span>
                                @endif
                            </div>
                        </div>

                        <div>
                            <div>
                                Delete Plant
                            </div>
                            <div>
                                <button type="button"
                                    class="bg-red-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded"
                                    onclick="handleClickDelAccButton(true)">Delete</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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

    <div class="fixed z-10 inset-0 overflow-y-auto modalDeleteAccLink hidden" role="dialog">
        <div class="flex items-end justify-center min-h-screen pt-1 px-4 pb-20 text-center sm:block sm:p-0">

            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form
                    action="{{ route('Plant.destroy', ['div_id' => $div_id, 'dept_id' => $dept_id, 'Plant' => $plant->id]) }}"
                    method="post" enctype="multipart/form-data">
                    @csrf
                    @method('DELETE')
                    <div class="bg-white px-4 pt-3 pb-4 sm:p-6 sm:pb-4">
                        <div class="">
                            <div class="mt-1 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 font-bold" id="modal-title">
                                    Confirmation
                                </h3>
                                <div class="mt-2">
                                    <p class="text-gray-500">
                                        You are about to delete this plant. Continue?
                                    </p>
                                </div>
                                <div class="mt-2">
                                    <input type="text" name="plant_id" value="{{ $plant->id }}" hidden>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Continue
                        </button>
                        <button type="button"
                            class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                            onclick="handleClickDelAccButton(false)">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
