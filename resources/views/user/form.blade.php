<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3">
            @if ($formType == 'New')
                <a href="{{ route('User.index') }}"
                    class="inline-flex items-center gap-2 text-sm font-semibold text-gray-500 hover:text-gray-700 transition duration-150 ease-in-out flex-shrink-0" style="align-self: flex-start;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 12H5" /><path d="M12 19l-7-7 7-7" />
                    </svg>
                    Users
                </a>
            @else
                <a href="{{ route('User.show', ['User' => $user->id]) }}"
                    class="inline-flex items-center gap-2 text-sm font-semibold text-gray-500 hover:text-gray-700 transition duration-150 ease-in-out flex-shrink-0" style="align-self: flex-start;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 12H5" /><path d="M12 19l-7-7 7-7" />
                    </svg>
                    {{ $user->name }}
                </a>
            @endif
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-gray-900 leading-tight">
                    {{ $pageTitle }}
                </h2>
                <p class="mt-0.5 text-sm text-gray-500">
                    Fill in the user's details, department and role.
                </p>
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
        .w-8 { width: 2rem; }
        .h-8 { height: 2rem; }
        .gap-2 { gap: 0.5rem; }
        .gap-3 { gap: 0.75rem; }
        .gap-5 { gap: 1.25rem; }
        .mb-5 { margin-bottom: 1.25rem; }
        .mb-6 { margin-bottom: 1.5rem; }
        .bg-indigo-50 { background-color: #eef2ff; }
        .text-indigo-600 { color: #4f46e5; }
        .bg-indigo-600 { background-color: #4f46e5; }
        .hover\:bg-indigo-700:hover { background-color: #4338ca; }
        .bg-red-50 { background-color: #fef2f2; }
        .text-red-600 { color: #dc2626; }
        .border-red-200 { border-color: #fecaca; }

        #user-form .card { background: #fff; border: 1px solid #e5e7eb; border-radius: 0.75rem; padding: 28px; box-shadow: 0 1px 2px rgba(20,20,19,0.04); }
        #user-form .form-columns { display: grid; grid-template-columns: 1fr; gap: 24px; align-items: start; }
        @media (min-width: 768px) {
            #user-form .form-columns { grid-template-columns: 1fr 1fr; }
        }
        #user-form .card-head { display: flex; align-items: center; gap: 10px; margin-bottom: 6px; }
        #user-form .card-icon { width: 32px; height: 32px; border-radius: 10px; background: #eef2ff; color: #4f46e5; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        #user-form .card-title { margin: 0; font-size: 13px; font-weight: 700; color: #14171a; text-transform: uppercase; letter-spacing: 0.04em; }
        #user-form .card-subtitle { font-size: 12px; color: #9a9d8f; margin: 0 0 20px 42px; line-height: 1.5; }
        #user-form label { display: block; font-size: 12.5px; font-weight: 600; color: #374151; margin-bottom: 6px; }
        #user-form input[type="text"],
        #user-form input[type="email"],
        #user-form input[type="password"],
        #user-form select {
            width: 100%;
            font-size: 13.5px;
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 0.625rem;
            background-color: #fff;
        }
        #user-form input:focus,
        #user-form select:focus {
            outline: none;
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.15);
        }
        #user-form .field { margin-bottom: 20px; }
        #user-form .field-error { font-size: 12px; color: #dc2626; margin-top: 6px; }
        #user-form .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        @media (max-width: 640px) { #user-form .grid-2 { grid-template-columns: 1fr; } }
        #user-form .field-static { font-size: 13.5px; color: #6b7280; padding: 10px 0; }
    </style>

    <div id="user-form" class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if ($errors->any())
                <div class="rounded-xl border border-red-200 bg-red-50 mb-6" style="padding: 14px 18px;">
                    <div class="font-bold text-red-600" style="font-size: 13px;">Please fix the following:</div>
                    <ul style="margin: 6px 0 0; padding-left: 18px;">
                        @foreach ($errors->all() as $each)
                            <li class="text-red-600" style="font-size: 12.5px;">{{ $each }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ $formRoute }}" method="POST">
                @csrf
                {{ method_field($methodField) }}

                <div class="form-columns mb-6">
                    <div class="card">
                        <div class="card-head">
                            <div class="card-icon">
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4" /><path d="M4 21v-1a8 8 0 0 1 16 0v1" /></svg>
                            </div>
                            <h3 class="card-title">General Details</h3>
                        </div>
                        <p class="card-subtitle">Contact details, department and role.</p>

                        <div class="field">
                            <label for="name">Name <span class="text-red-600">*</span></label>
                            <input type="text" name="name" id="name"
                                placeholder="Full name"
                                value="{{ $formType == 'Edit' ? $user->name : old('name') }}"
                                required>
                            @error('name')
                                <div class="field-error">{{ $message }}</div>
                            @enderror
                        </div>

                        @if ($formType == 'New')
                            <div class="field">
                                <label for="email">Email Address <span class="text-red-600">*</span></label>
                                <input type="email" name="email" id="email"
                                    placeholder="name@company.com"
                                    value="{{ old('email') }}"
                                    required>
                                @error('email')
                                    <div class="field-error">{{ $message }}</div>
                                @enderror
                            </div>
                        @else
                            <div class="field">
                                <label for="email">Email Address</label>
                                <div class="field-static">{{ $user->email }}</div>
                            </div>
                        @endif

                        <div class="field">
                            <label for="phone_number">Phone Number <span class="text-gray-400" style="font-weight: 400;">(optional)</span></label>
                            <input type="text" name="phone_number" id="phone_number"
                                placeholder="e.g. 012-345 6789"
                                value="{{ $formType == 'Edit' ? $user->phone_number : old('phone_number') }}">
                            @error('phone_number')
                                <div class="field-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="grid-2">
                            <div class="field">
                                <label for="department_id">Department / Division <span class="text-red-600">*</span></label>
                                <select name="department_id" id="department_id" required>
                                    <option value="" hidden selected disabled>-- Please select one department --</option>
                                    @forelse ($departments as $item)
                                        @if ($formType == 'New')
                                            <option value="{{ $item->id }}"
                                                {{ old('department_id') == $item->id ? 'selected' : '' }}>
                                                {{ $item->name }}
                                            </option>
                                        @else
                                            <option value="{{ $item->id }}"
                                                @if (optional($user->department->first())->id == $item->id) selected @endif>
                                                {{ $item->name }}</option>
                                        @endif
                                    @empty
                                        No Data
                                    @endforelse
                                </select>
                                @error('department_id')
                                    <div class="field-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="field">
                                <label for="designation">Designation <span class="text-red-600">*</span></label>
                                <input type="text" name="designation" id="designation"
                                    placeholder="e.g. Executive"
                                    value="{{ $formType == 'Edit' ? $user->designation : old('designation') }}"
                                    required>
                                @error('designation')
                                    <div class="field-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="field" style="margin-bottom: 0;">
                            <label for="role_id">Role <span class="text-red-600">*</span></label>
                            <select name="role_id" id="role_id" required>
                                <option value="" hidden selected disabled>-- Please select one role --</option>
                                @forelse ($roles as $item)
                                    @if ($formType == 'New')
                                        <option value="{{ $item->id }}"
                                            {{ old('role_id') == $item->id ? 'selected' : '' }}>
                                            {{ $item->name_display }}
                                        </option>
                                    @else
                                        <option value="{{ $item->id }}"
                                            @if (optional($user->groups->first())->id == $item->id) selected @endif>
                                            {{ $item->name_display }}</option>
                                    @endif
                                @empty
                                    No Data
                                @endforelse
                            </select>
                            @error('role_id')
                                <div class="field-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    @if ($formType == 'New')
                        <div class="card">
                            <div class="card-head">
                                <div class="card-icon">
                                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="10" rx="2" /><path d="M7 11V7a5 5 0 0 1 10 0v4" /></svg>
                                </div>
                                <h3 class="card-title">Password</h3>
                            </div>
                            <p class="card-subtitle">Minimum eight characters, at least one uppercase letter, one lowercase letter and one number.</p>

                            <div class="field">
                                <label for="password">Password <span class="text-red-600">*</span></label>
                                <input type="password" name="password" id="password"
                                    placeholder="Password"
                                    value="{{ old('password') }}">
                                @error('password')
                                    <div class="field-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="field" style="margin-bottom: 0;">
                                <label for="password_confirmation">Password Confirmation <span class="text-red-600">*</span></label>
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                    placeholder="Confirm password"
                                    value="{{ old('password_confirmation') }}">
                                @error('password_confirmation')
                                    <div class="field-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    @endif

                    @if ($formType == 'Edit')
                        <div class="card">
                            <div class="card-head">
                                <div class="card-icon">
                                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="10" rx="2" /><path d="M7 11V7a5 5 0 0 1 10 0v4" /></svg>
                                </div>
                                <h3 class="card-title">Account</h3>
                            </div>
                            <p class="card-subtitle">Control whether this user can sign in.</p>

                            <div class="grid-2">
                                <div class="field" style="margin-bottom: 0;">
                                    <label for="is_enabled">Account Status</label>
                                    <select name="is_enabled" id="is_enabled">
                                        <option value="1" @if ($user->is_enabled == 1) selected @endif>Enabled</option>
                                        <option value="0" @if ($user->is_enabled == 0) selected @endif>Disabled</option>
                                    </select>
                                </div>

                                <div class="field" style="margin-bottom: 0;">
                                    <label for="is_locked">Locked Status</label>
                                    <select name="is_locked" id="is_locked">
                                        <option value="1" @if ($user->is_locked == 1) selected @endif>Locked</option>
                                        <option value="0" @if ($user->is_locked == 0) selected @endif>Unlocked</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="flex items-center gap-3">
                    @if ($formType == 'New')
                        <a href="{{ route('User.index') }}"
                            class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-bold text-gray-700 hover:bg-gray-50 transition duration-150 ease-in-out">
                            Cancel
                        </a>
                    @else
                        <a href="{{ route('User.show', ['User' => $user->id]) }}"
                            class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-bold text-gray-700 hover:bg-gray-50 transition duration-150 ease-in-out">
                            Cancel
                        </a>
                    @endif
                    <button type="submit"
                        class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-bold text-white hover:bg-indigo-700 transition duration-150 ease-in-out">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5" /></svg>
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
