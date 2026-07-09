<x-app-layout>
    <x-slot name="header">
        <div class="grid md:grid-cols-2">
            <div class="flex justify-start">
                <div class="inline-block mr-20">
                    <a href="{{ route('StateBranch.index') }}">Back</a>
                </div>
                <div>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ $branch->name }}
                    </h2>
                </div>
            </div>
            <div class="text-right">
                <a class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded"
                    href="{{ route('StateBranch.edit', ['StateBranch' => $branch->id]) }}">Edit</a>
                <button type="button" class="bg-red-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded"
                    onclick="handleClickDelAccButton(true)">Delete</button>
            </div>
        </div>
    </x-slot>

    {{-- Alert message pop up --}}
    @if (session('alertColor'))
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 my-2">
            @component('components.Alert')
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
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <div>
                                State
                            </div>
                            <div>
                                {{ $state->name }}
                            </div>
                        </div>

                        <div>
                            <div>
                                Branch Name
                            </div>
                            <div>
                                {{ $branch->name }}
                            </div>
                        </div>

                        <div>
                            <div>
                                Branch PIC
                            </div>
                            <div>
                                @if ($branch->pic_branch->isNotEmpty())
                                    <ul class="list-disc">
                                        @foreach ($branch->pic_branch as $user)
                                            <div class="grid md:grid-cols-3 py-2">
                                                <div class="col-span-2">
                                                    <li>{{ $user->name }} - {{ $user->email }}</li>
                                                </div>
                                                <div class="col-span-1 text-right">
                                                    <button type="button"
                                                        class="bg-red-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded"
                                                        onclick="handleClickRemoveButton(true, {{ $user->id }})">Remove</button>
                                                </div>
                                            </div>

                                            <div class="fixed z-10 inset-0 overflow-y-auto modalRemovePICLink hidden"
                                                role="dialog">
                                                <div
                                                    class="flex items-end justify-center min-h-screen pt-1 px-4 pb-20 text-center sm:block sm:p-0">
                                                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                                                        aria-hidden="true"></div>
                                                    <div
                                                        class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                                        <form
                                                            action="{{ route('pic_remove', ['branch_id' => $branch->id, 'user_id' => $user->id]) }}"
                                                            method="post" enctype="multipart/form-data">
                                                            @csrf
                                                            @method('POST')
                                                            <div class="bg-white px-4 pt-3 pb-4 sm:p-6 sm:pb-4">
                                                                <div class="">
                                                                    <div
                                                                        class="mt-1 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                                                        <h3 class="text-lg leading-6 font-medium text-gray-900 font-bold"
                                                                            id="modal-title">
                                                                            Confirmation
                                                                        </h3>
                                                                        <div class="mt-2">
                                                                            <p class="text-gray-500">
                                                                                You are about to remove
                                                                                <strong>{{ $user->name }}</strong> as
                                                                                PIC for
                                                                                <strong>{{ $branch->name }}</strong>
                                                                                branch.
                                                                                Continue?
                                                                            </p>
                                                                        </div>
                                                                        <div class="mt-2">
                                                                            <input type="text" name="branch_id"
                                                                                value="{{ $branch->id }}" hidden>
                                                                            <input type="text" name="user_id"
                                                                                value="{{ $user->id }}" hidden>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div
                                                                class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                                                <button type="submit"
                                                                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                                                                    Continue
                                                                </button>
                                                                <button type="button"
                                                                    class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                                                                    onclick="handleClickRemoveButton(false, {{ $user->id }})">
                                                                    Cancel
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </ul>
                                @else
                                    <span class="italic">Not available</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-2">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="pb-4 font-bold">
                        Action
                    </div>

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <div>
                                Status
                            </div>
                            <div>
                                <span
                                    class="inline-block @if ($branch->is_enabled == 1) bg-green-200 @elseif($branch->is_enabled == 0) bg-red-200 @else bg-green-200 @endif rounded-full py-1 px-3">{{ $branch->is_enabled == 1 ? 'Enabled' : 'Disabled' }}</span>
                            </div>
                        </div>

                        <div>
                            <div>
                                Add PIC
                            </div>
                            <div>
                                <form method="POST" action="{{ route('pic_add', ['branch_id' => $branch->id]) }}">
                                    @csrf
                                    <div class="grid md:grid-cols-2 gap-4">
                                        <div>
                                            <select name="pic_id" id="pic_id"
                                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                                <option hidden selected value="">Please choose</option>
                                                @forelse ($users as $user)
                                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                @empty
                                                    <option disabled>No available users</option>
                                                @endforelse
                                            </select>
                                        </div>
                                        <div class="flex items-end">
                                            <button type="submit"
                                                class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                                                Add PIC
                                            </button>
                                        </div>
                                    </div>
                                </form>
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
                ;
                $('.modalDeleteAccLink').hide();
            }

        }

        function handleClickRemoveButton(status, id) {
            if (status) {
                $('.modalRemovePICLink').show();
            } else {
                $('.modalRemovePICLink').hide();
            }
        }
    </script>

    <div class="fixed z-10 inset-0 overflow-y-auto modalDeleteAccLink hidden" role="dialog">
        <div class="flex items-end justify-center min-h-screen pt-1 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form action="{{ route('StateBranch.destroy', ['StateBranch' => $branch->id]) }}" method="post"
                    enctype="multipart/form-data">
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
                                        You are about to delete this branch. Continue?
                                    </p>
                                </div>
                                <div class="mt-2">
                                    <input type="text" name="branch_id" value="{{ $branch->id }}" hidden>
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
