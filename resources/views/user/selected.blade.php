<x-app-layout>
    <x-slot name="header">
        <div class="grid md:grid-cols-2">
            <div class="flex justify-start">
                <div class="inline-block mr-20">
                    <a href="{{ route('User.index', ['page' => $pageNum]) }}">Back</a>
                </div>
                <div>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        User #{{ $user->id }}
                    </h2>
                </div>
            </div>
            <div class="text-right">
                <a class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded"
                    href="{{ route('User.edit', ['User' => $user->id]) }}">Edit</a>
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
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <div>
                                Name
                            </div>
                            <div>
                                {{ $user->name }}
                            </div>
                        </div>

                        <div>
                            <div>
                                Email
                            </div>
                            <div>
                                {{ $user->email }}
                            </div>
                        </div>

                        <div>
                            <div>
                                Department/Division
                            </div>
                            <div>
                                @if ($user->department->isNotEmpty())
                                    <ul class="list-disc">
                                        @foreach ($user->department as $department)
                                            <li>{{ $department->name }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <span class="italic">Not available</span>
                                @endif
                            </div>
                        </div>

                        <div>
                            <div>
                                Phone Number
                            </div>
                            <div>
                                @if ($user->phone_number)
                                    {{ $user->phone_number }}
                                @else
                                    <span class="italic">Not available</span>
                                @endif
                            </div>
                        </div>

                        <div>
                            <div>
                                Designation
                            </div>
                            <div>
                                @if ($user->designation)
                                    {{ $user->designation }}
                                @else
                                    <span class="italic">Not available</span>
                                @endif
                            </div>
                        </div>

                        <div>
                            <div>
                                Role
                            </div>
                            <div>
                                @if ($user->groups->isNotEmpty())
                                    <ul class="list-disc">
                                        @foreach ($user->groups as $group)
                                            <li>{{ $group->name_display }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <span class="italic">Not available</span>
                                @endif
                            </div>
                        </div>

                        @if ($user->groups->first()->name == 'branch_pic')

                            <div></div>

                            <div>
                                <div>
                                    Branch
                                </div>
                                <div>
                                    @if ($user->pic_branch->isNotEmpty())
                                        <ul class="list-disc">
                                            @foreach ($user->pic_branch as $branch)
                                                <li>{{ $branch->name }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <span class="italic">Not available</span>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-2">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="pb-4 font-bold">
                        Account
                    </div>

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <div>
                                Status
                            </div>
                            <div>
                                <span
                                    class="inline-block @if ($user->is_enabled == 1) bg-green-200 @elseif($user->is_enabled == 0) bg-red-200 @else bg-green-200 @endif rounded-full py-1 px-3">{{ $user->is_enabled == 1 ? 'Enabled' : 'Disabled' }}</span>
                                <span
                                    class="inline-block @if ($user->is_locked == 0) bg-green-200 @elseif($user->is_locked == 1) bg-red-200 @else bg-green-200 @endif rounded-full py-1 px-3">{{ $user->is_locked == 1 ? 'Locked' : 'Unlocked' }}</span>
                            </div>
                        </div>

                        <div>
                            <div>
                                Last Password Reset
                            </div>
                            <div>
                                @if (!empty($user->last_password_reset))
                                    {{ date('d/m/Y h:i A', strtotime($user->last_password_reset)) }}
                                @else
                                    -
                                @endif
                            </div>
                            <div>
                                <button type="button"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded"
                                    onclick="handleClickPasswordResetButton(true)">Send Password Reset Link</button>
                            </div>
                        </div>

                        <div>
                            <div>
                                Password Expiry
                            </div>
                            <div>
                                Date: {{ date('d/m/Y h:i A', strtotime($user->password_expiry_date)) }}
                            </div>
                            <div>
                                Status:
                                @if (\Carbon\Carbon::parse($user->password_expiry_date)->lessThanOrEqualTo(\Carbon\Carbon::now()))
                                    <span style="color:red;">Expired</span>
                                @else
                                    <span style="color:green;">Valid</span>
                                @endif
                            </div>
                        </div>

                        <div>
                            <div>
                                Delete Account

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
        function handleClickPasswordResetButton(status) {
            if (status) {
                $('.modalMailPasswordResetLink').show();
            } else {
                $('.modalMailPasswordResetLink').hide();
            }

        }
    </script>

    <div class="fixed z-10 inset-0 overflow-y-auto modalMailPasswordResetLink hidden" role="dialog">
        <div class="flex items-end justify-center min-h-screen pt-1 px-4 pb-20 text-center sm:block sm:p-0">

            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form action="{{ route('sendMailPasswordResetLink') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="bg-white px-4 pt-3 pb-4 sm:p-6 sm:pb-4">
                        <div class="">
                            <div class="mt-1 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 font-bold" id="modal-title">
                                    Confirmation
                                </h3>
                                <div class="mt-2">
                                    <p class="text-gray-500">
                                        You are about to send email to this person. Continue?
                                    </p>
                                </div>
                                <div class="mt-2">
                                    <input type="text" name="email" value="{{ $user->email }}" hidden>
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
                            onclick="handleClickPasswordResetButton(false)">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function handleClickDelAccButton(status) {
            if (status) {
                $('.modalMailPasswordResetLink').show();
                $('.modalDeleteAccLink').show();
            } else {
                $('.modalMailPasswordResetLink').hide();
                $('.modalDeleteAccLink').hide();
            }

        }
    </script>

    <div class="fixed z-10 inset-0 overflow-y-auto modalDeleteAccLink hidden" role="dialog">
        <div class="flex items-end justify-center min-h-screen pt-1 px-4 pb-20 text-center sm:block sm:p-0">

            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form action="{{ route('User.destroy', ['User' => $user->id]) }}" method="post"
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
                                        You are about to delete this account. Continue?
                                    </p>
                                </div>
                                <div class="mt-2">
                                    <input type="text" name="user_id" value="{{ $user->id }}" hidden>
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
