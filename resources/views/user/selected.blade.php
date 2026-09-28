<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3">
            <a href="{{ route('User.index', ['page' => $pageNum]) }}"
                class="inline-flex items-center gap-2 text-sm font-semibold text-gray-500 hover:text-gray-700 transition duration-150 ease-in-out flex-shrink-0" style="align-self: flex-start;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 12H5" /><path d="M12 19l-7-7 7-7" />
                </svg>
                Users
            </a>

            <div class="flex items-center justify-between gap-4">
                <div>
                    <div class="text-xs font-bold uppercase tracking-wider text-gray-400">User #{{ $user->id }}</div>
                    <h2 class="text-2xl font-bold tracking-tight text-gray-900 leading-tight mt-0.5">
                        {{ $user->name }}
                    </h2>
                </div>

                <div class="flex items-center gap-3 flex-shrink-0">
                    <button
                        class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-bold text-red-600 hover:bg-red-50 hover:border-red-300 transition duration-150 ease-in-out"
                        onclick="handleClickDelAccButton(true)">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18" /><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" /><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" /></svg>
                        Delete
                    </button>
                    <a href="{{ route('User.edit', ['User' => $user->id]) }}"
                        class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-bold text-white hover:bg-indigo-700 transition duration-150 ease-in-out">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" /><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" /></svg>
                        Edit
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    {{--
        The compiled public/css/app.css on this install predates this markup and can't
        currently be rebuilt (Laravel Mix's toolchain doesn't run on this machine), so a
        handful of Tailwind utilities below were never generated. These rules backfill
        exactly those classes with their standard Tailwind values.
    --}}
    <style>
        .tracking-wider { letter-spacing: 0.05em; }
        .tracking-tight { letter-spacing: -0.025em; }
        .text-2xl { font-size: 1.5rem; line-height: 2rem; }
        .rounded-xl { border-radius: 0.75rem; }
        .rounded-2xl { border-radius: 1rem; }
        .w-8 { width: 2rem; }
        .h-8 { height: 2rem; }
        .w-14 { width: 3.5rem; }
        .h-14 { height: 3.5rem; }
        .w-16 { width: 4rem; }
        .h-16 { height: 4rem; }
        .gap-2 { gap: 0.5rem; }
        .gap-3 { gap: 0.75rem; }
        .gap-5 { gap: 1.25rem; }
        .mb-5 { margin-bottom: 1.25rem; }
        .mb-6 { margin-bottom: 1.5rem; }
        .px-2\.5 { padding-left: 0.625rem; padding-right: 0.625rem; }
        .py-0\.5 { padding-top: 0.125rem; padding-bottom: 0.125rem; }
        .bg-indigo-50 { background-color: #eef2ff; }
        .text-indigo-600 { color: #4f46e5; }
        .text-indigo-700 { color: #4338ca; }
        .bg-indigo-600 { background-color: #4f46e5; }
        .hover\:bg-indigo-700:hover { background-color: #4338ca; }
        .hover\:bg-red-50:hover { background-color: #fef2f2; }
        .hover\:border-red-300:hover { border-color: #fca5a5; }
        .bg-red-50 { background-color: #fef2f2; }
        .text-red-600 { color: #dc2626; }
        .bg-red-600 { background-color: #dc2626; }
        .hover\:bg-red-700:hover { background-color: #b91c1c; }
        .bg-green-100 { background-color: #dcfce7; }
        .text-green-700 { color: #15803d; }
        .bg-yellow-100 { background-color: #fef9c3; }
        .text-yellow-800 { color: #854d0e; }

        #user-detail .card { background: #fff; border: 1px solid #e5e7eb; border-radius: 0.75rem; padding: 28px; box-shadow: 0 1px 2px rgba(20,20,19,0.04); }
        #user-detail .card-head { display: flex; align-items: center; gap: 10px; margin-bottom: 22px; padding-bottom: 16px; border-bottom: 1px solid #f0efe9; }
        #user-detail .card-icon { width: 32px; height: 32px; border-radius: 10px; background: #eef2ff; color: #4f46e5; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        #user-detail .card-title { margin: 0; font-size: 13px; font-weight: 700; color: #14171a; text-transform: uppercase; letter-spacing: 0.04em; }
        #user-detail .field-label { font-size: 12px; color: #9a9d8f; font-weight: 600; }
        #user-detail .chip { display: inline-flex; align-items: center; padding: 5px 11px; border-radius: 8px; background: #f4f2ea; color: #4b4e44; font-size: 12px; font-weight: 600; margin: 0 6px 6px 0; }
    </style>

    <div id="user-detail" class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Alert message pop up --}}
            @if (session('alertColor'))
                <div class="mb-6">
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

            @php
                $initials = function (?string $name) {
                    $parts = array_filter(preg_split('/\s+/', trim((string) $name)));
                    $letters = array_map(fn ($p) => mb_substr($p, 0, 1), array_slice($parts, 0, 2));
                    return mb_strtoupper(implode('', $letters)) ?: '—';
                };
                $isBranchPic = optional($user->groups->first())->name == 'branch_pic';
            @endphp

            {{-- Quick stats --}}
            <div class="grid md:grid-cols-3 gap-5 mb-6">
                <div class="card">
                    <div class="field-label">Status</div>
                    <div class="mt-1">
                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-bold {{ $user->is_enabled == 1 ? 'bg-green-100 text-green-700' : 'bg-red-50 text-red-600' }}">
                            {{ $user->is_enabled == 1 ? 'Enabled' : 'Disabled' }}
                        </span>
                    </div>
                </div>
                <div class="card">
                    <div class="field-label">Login Access</div>
                    <div class="mt-1">
                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-bold {{ $user->is_locked == 1 ? 'bg-red-50 text-red-600' : 'bg-green-100 text-green-700' }}">
                            {{ $user->is_locked == 1 ? 'Locked' : 'Unlocked' }}
                        </span>
                    </div>
                </div>
                <div class="card">
                    <div class="field-label">Password Expiry</div>
                    <div class="text-sm font-bold text-gray-900 mt-1">
                        {{ date('d/m/Y', strtotime($user->password_expiry_date)) }}
                        @if (\Carbon\Carbon::parse($user->password_expiry_date)->lessThanOrEqualTo(\Carbon\Carbon::now()))
                            <span class="text-red-600">· Expired</span>
                        @else
                            <span class="text-green-700">· Valid</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="grid md:grid-cols-3 gap-5 items-start mb-5">
                {{-- General --}}
                <div class="card md:col-span-2">
                    <div class="card-head">
                        <div class="card-icon">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4" /><path d="M4 21v-1a8 8 0 0 1 16 0v1" /></svg>
                        </div>
                        <h3 class="card-title">General</h3>
                    </div>

                    <div class="flex items-center gap-3 mb-5">
                        <span class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-indigo-50 text-indigo-700 font-bold flex-shrink-0" style="font-size: 18px;">
                            {{ $initials($user->name) }}
                        </span>
                        <div>
                            <div class="text-sm font-bold text-gray-900">{{ $user->name }}</div>
                            <div class="text-xs text-gray-500 mt-0.5">{{ $user->email }}</div>
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-5">
                        <div>
                            <div class="field-label mb-1">Phone Number</div>
                            <div class="text-xs font-semibold text-gray-800">{{ $user->phone_number ?: '—' }}</div>
                        </div>
                        <div>
                            <div class="field-label mb-1">Designation</div>
                            <div class="text-xs font-semibold text-gray-800">{{ $user->designation ?: '—' }}</div>
                        </div>

                        <div class="pt-4 border-t border-gray-100">
                            <div class="field-label mb-2">Department / Division</div>
                            @if ($user->department->isNotEmpty())
                                @foreach ($user->department as $department)
                                    <span class="chip">{{ $department->name }}</span>
                                @endforeach
                            @else
                                <span class="text-xs text-gray-500 italic">Not available</span>
                            @endif
                        </div>

                        <div class="pt-4 border-t border-gray-100">
                            <div class="field-label mb-2">Role</div>
                            @if ($user->groups->isNotEmpty())
                                @foreach ($user->groups as $group)
                                    <span class="chip">{{ $group->name_display }}</span>
                                @endforeach
                            @else
                                <span class="text-xs text-gray-500 italic">Not available</span>
                            @endif
                        </div>

                        @if ($isBranchPic)
                            <div class="pt-4 border-t border-gray-100" style="grid-column: 1 / -1;">
                                <div class="field-label mb-2">Branch</div>
                                @if (optional($user->pic_branch)->isNotEmpty())
                                    @foreach ($user->pic_branch as $branch)
                                        <span class="chip">{{ $branch->name }}</span>
                                    @endforeach
                                @else
                                    <span class="text-xs text-gray-500 italic">Not available</span>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Account --}}
                <div class="card">
                    <div class="card-head">
                        <div class="card-icon">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="10" rx="2" /><path d="M7 11V7a5 5 0 0 1 10 0v4" /></svg>
                        </div>
                        <h3 class="card-title">Account</h3>
                    </div>

                    <div class="mb-5">
                        <div class="field-label mb-1">Last Password Reset</div>
                        <div class="text-xs font-semibold text-gray-800">
                            {{ !empty($user->last_password_reset) ? date('d/m/Y h:i A', strtotime($user->last_password_reset)) : '—' }}
                        </div>
                    </div>

                    <button type="button"
                        class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-xs font-bold text-gray-700 hover:bg-gray-50 transition duration-150 ease-in-out"
                        onclick="handleClickPasswordResetButton(true)">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2" /><path d="M3 7l9 6 9-6" /></svg>
                        Send Password Reset Link
                    </button>
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

        function handleClickDelAccButton(status) {
            if (status) {
                $('.modalDeleteAccLink').show();
            } else {
                $('.modalDeleteAccLink').hide();
            }
        }
    </script>

    {{-- Password Reset Modal --}}
    <div class="fixed z-10 inset-0 overflow-y-auto modalMailPasswordResetLink" role="dialog" hidden>
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full">
                <form action="{{ route('sendMailPasswordResetLink') }}" method="post">
                    @csrf
                    <div style="padding: 32px 32px 8px; text-align: left;">
                        <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mb-5">
                            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2" /><path d="M3 7l9 6 9-6" /></svg>
                        </div>
                        <h3 class="font-bold text-gray-900" style="font-size: 19px; margin: 0 0 6px;" id="modal-title">
                            Send Password Reset Link
                        </h3>
                        <p class="text-gray-500" style="font-size: 13px; line-height: 1.5; margin: 0 0 4px;">
                            You are about to send a password reset email to <strong>{{ $user->email }}</strong>. Continue?
                        </p>
                        <input type="text" name="email" value="{{ $user->email }}" hidden>
                    </div>
                    <div class="flex items-center justify-end gap-2" style="padding: 16px 32px 28px; border-top: 1px solid #f0efe9;">
                        <button type="button"
                            class="inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white font-semibold text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            style="font-size: 13.5px;"
                            onclick="handleClickPasswordResetButton(false)">
                            Cancel
                        </button>
                        <button type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 font-bold text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            style="font-size: 13.5px;">
                            Yes, Send
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Delete Modal --}}
    <div class="fixed z-10 inset-0 overflow-y-auto modalDeleteAccLink" role="dialog" hidden>
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full">
                <form action="{{ route('User.destroy', ['User' => $user->id]) }}" method="post">
                    @csrf
                    @method('DELETE')
                    <div style="padding: 32px 32px 8px; text-align: left;">
                        <div class="w-14 h-14 rounded-2xl bg-red-50 text-red-600 flex items-center justify-center mb-5">
                            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18" /><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" /><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" /></svg>
                        </div>
                        <h3 class="font-bold text-gray-900" style="font-size: 19px; margin: 0 0 6px;" id="modal-title">
                            Delete Account
                        </h3>
                        <p class="text-gray-500" style="font-size: 13px; line-height: 1.5; margin: 0 0 4px;">
                            You are about to delete <strong>{{ $user->name }}</strong>'s account. This action cannot be undone.
                        </p>
                        <input type="text" name="user_id" value="{{ $user->id }}" hidden>
                    </div>
                    <div class="flex items-center justify-end gap-2" style="padding: 16px 32px 28px; border-top: 1px solid #f0efe9;">
                        <button type="button"
                            class="inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white font-semibold text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            style="font-size: 13.5px;"
                            onclick="handleClickDelAccButton(false)">
                            Cancel
                        </button>
                        <button type="submit"
                            class="bg-red-600 hover:bg-red-700 inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 font-bold text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                            style="font-size: 13.5px;">
                            Yes, Delete
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
