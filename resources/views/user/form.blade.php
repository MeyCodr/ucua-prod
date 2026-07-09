<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $pageTitle }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @forelse ($errors->all() as $each)
                <p style="color:red">*{{ $each }}</p>
            @empty
            @endforelse
        </div>

        {{-- Form --}}
        <form action="{{ $formRoute }}" method="POST">
            @csrf
            {{ method_field($methodField) }}
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-2">
                <div class="grid md:grid-cols-3">
                    <div class="col-span-1">
                        <div class="text-lg p-5">
                            General Details
                            <div class="text-gray-500 text-sm">Fill in the details</div>
                        </div>

                    </div>
                    <div class="col-span-2">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-2">
                            <div class="p-6 bg-white border-b border-gray-200">
                                <div>
                                    <div class="grid md:grid-cols-3 py-2">
                                        <label for="name" class="col-span-1 self-center">Name: <span
                                                class="text-red-600">*</span></label>
                                        <div class="col-span-2">
                                            <input type="text" name="name" id="name" class="w-full rounded"
                                                placeholder="Name"
                                                value="{{ $formType == 'Edit' ? $user->name : old('name') }}" required>
                                            @error('name')
                                                <span class="text-red-600">* {{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    @if ($formType == 'New')
                                        <div class="grid md:grid-cols-3 py-2">
                                            <label for="email" class="col-span-1 self-center">Email Address: <span
                                                    class="text-red-600">*</span></label>
                                            <div class="col-span-2">
                                                <input type="email" name="email" id="email"
                                                    class="w-full rounded" placeholder="Email"
                                                    value="{{ $formType == 'Edit' ? $user->email : old('email') }}"
                                                    required>
                                                @error('email')
                                                    <span class="text-red-600">* {{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    @else
                                        <div class="grid md:grid-cols-3 py-2">
                                            <label for="email" class="col-span-1 self-center">Email Address:</label>
                                            <div class="col-span-2">
                                                <p class="text-gray-700">{{ $formType == 'Edit' ? $user->email : '' }}
                                                </p>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="grid md:grid-cols-3 py-2">
                                        <label for="phone_number" class="col-span-1 self-center">Phone Number: <span
                                                class="text-red-600">*</span></label>
                                        <div class="col-span-2">
                                            <input type="text" name="phone_number" id="phone_number"
                                                class="w-full rounded" placeholder="Phone Number"
                                                value="{{ $formType == 'Edit' ? $user->phone_number : old('phone_number') }}"
                                                required>
                                            @error('phone_number')
                                                <span class="text-red-600">* {{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="grid md:grid-cols-3 py-2">
                                        <label for="department_id" class="col-span-1 self-center">Department/Division
                                            Name: <span class="text-red-600">*</span></label>
                                        <div class="col-span-2">
                                            <select name="department_id" id="department_id" class="form-control"
                                                required>
                                                <option value="" hidden selected disabled>-- Please select one
                                                    department --
                                                </option>
                                                @forelse ($departments as $item)
                                                    @if ($formType == 'New')
                                                        <option value="{{ $item->id }}"
                                                            {{ old('department_id') == $item->id ? 'selected' : '' }}>
                                                            {{ $item->name }}
                                                        </option>
                                                    @else
                                                        <option value="{{ $item->id }}"
                                                            @if ($user->department->first()->id == $item->id) selected @endif>
                                                            {{ $item->name }}</option>
                                                    @endif
                                                @empty
                                                    No Data
                                                @endforelse
                                                {{-- @foreach ($departments as $dept)
                                                    <option value="{{ $dept->id }}"
                                                        {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                                                        {{ $dept->name }}
                                                    </option>
                                                @endforeach --}}
                                            </select>
                                            @error('department_id')
                                                <span class="text-red-600">* {{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="grid md:grid-cols-3 py-2">
                                        <label for="designation" class="col-span-1 self-center">Designation: <span
                                                class="text-red-600">*</span></label>
                                        <div class="col-span-2">
                                            <input type="text" name="designation" id="designation"
                                                class="w-full rounded" placeholder="Designation"
                                                value="{{ $formType == 'Edit' ? $user->designation : old('designation') }}"
                                                required>
                                            @error('designation')
                                                <span class="text-red-600">* {{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="grid md:grid-cols-3 py-2">
                                        <label for="role_id" class="col-span-1 self-center">Role: <span
                                                class="text-red-600">*</span></label>
                                        <select name="role_id" id="role_id" class="col-span-2 w-full rounded"
                                            required>
                                            <option value="" hidden selected disabled>-- Please select one role --
                                            </option>
                                            @forelse ($roles as $item)
                                                @if ($formType == 'New')
                                                    <option value="{{ $item->id }}"
                                                        {{ old('role_id') == $item->id ? 'selected' : '' }}>
                                                        {{ $item->name_display }}
                                                    </option>
                                                @else
                                                    <option value="{{ $item->id }}"
                                                        @if ($user->groups->first()->id == $item->id) selected @endif>
                                                        {{ $item->name_display }}</option>
                                                @endif
                                            @empty
                                                No Data
                                            @endforelse
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if ($formType == 'New')
                    {{-- Password --}}
                    <div class="grid md:grid-cols-3">
                        <div class="col-span-1">
                            <div class="text-lg p-5">
                                <div class="align-middle">Password</div>
                                <div class="text-gray-500 text-sm">Minimum eight characters, at least one uppercase
                                    letter, one lowercase letter and one number</div>
                            </div>
                        </div>
                        <div class="col-span-2">
                            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-2">
                                <div class="p-6 bg-white border-b border-gray-200">
                                    <div>
                                        <div class="grid md:grid-cols-3 py-2">
                                            <label for="password" class="col-span-1 self-center">Password: <span
                                                    class="text-red-600">*</span></label>
                                            <div class="col-span-2">
                                                <input type="password" name="password" id="password"
                                                    class="w-full rounded" placeholder="Password"
                                                    value="{{ old('password') }}">
                                                @error('password')
                                                    <span class="text-red-600">* {{ $message }}</span>
                                                @enderror
                                            </div>

                                        </div>

                                        <div class="grid md:grid-cols-3 py-2">
                                            <label for="password_confirmation" class="col-span-1 self-center">Password
                                                Confirmation: <span class="text-red-600">*</span></label>
                                            <div class="col-span-2">
                                                <input type="password" name="password_confirmation"
                                                    id="password_confirmation" class="w-full rounded"
                                                    placeholder="Password Confirmation"
                                                    value="{{ old('password_confirmation') }}">
                                                @error('password_confirmation')
                                                    <span class="text-red-600">* {{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($formType == 'Edit')
                    <div class="grid md:grid-cols-3">
                        <div class="col-span-1">
                            <div class="text-lg p-5">
                                <div class="align-middle">Account</div>
                            </div>
                        </div>
                        <div class="col-span-2">
                            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-2">
                                <div class="p-6 bg-white border-b border-gray-200">
                                    <div>
                                        <div class="grid md:grid-cols-3 py-2">
                                            <label for="is_enabled" class="col-span-1 self-center">Account
                                                Status:</label>
                                            <select name="is_enabled" id="is_enabled">
                                                <option value="1"
                                                    @if ($user->is_enabled == 1) selected @endif>
                                                    Enabled</option>
                                                <option value="0"
                                                    @if ($user->is_enabled == 0) selected @endif>
                                                    Disabled</option>
                                            </select>
                                        </div>

                                        <div class="grid md:grid-cols-3 py-2">
                                            <label for="is_locked" class="col-span-1 self-center">Locked
                                                Status:</label>
                                            <select name="is_locked" id="is_locked">
                                                <option value="1"
                                                    @if ($user->is_locked == 1) selected @endif>
                                                    Locked</option>
                                                <option value="0"
                                                    @if ($user->is_locked == 0) selected @endif>
                                                    Unlocked</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Submit Button --}}
                <div class="grid md:grid-cols-3">
                    <div class="col-span-1">
                    </div>
                    <div class="col-span-2 ">
                        <div class="sm:px-1 md:px-0">
                            <button class="border-green-600 bg-green-600 w-full py-2 rounded mt-3 text-white"
                                type="submit">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
