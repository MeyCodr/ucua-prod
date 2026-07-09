<x-app-layout>
    <x-slot name="header">
        <div class="grid md:grid-cols-1">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $formMode }} Branch Form
                </h2>
            </div>
        </div>
    </x-slot>



    <div class="py-6">
        <form action="{{ route('StateBranch.update', ['StateBranch' => $branch->id]) }}" method="post"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="text" name="branchId" value="{{ $branch->id }}" hidden>

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
                                        <label for="state_id" class="col-span-1 self-center">State: <span
                                                class="text-red-600">*</span></label>
                                        <select class="col-span-2 w-full rounded" id="state_id" name="state_id"
                                            required>
                                            <option hidden selected value="">Please choose</option>
                                            @foreach ($states as $state)
                                                <option value="{{ $state->id }}"
                                                    {{ $branch->state_id == $state->id ? 'selected' : '' }}>
                                                    {{ $state->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('state_id')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="grid md:grid-cols-3 py-2">
                                        <label for="branch_name" class="col-span-1 self-center">Branch Name: <span
                                                class="text-red-600">*</span></label>
                                        <div class="col-span-2">
                                            <input type="text" name="branch_name" id="branch_name"
                                                class="w-full rounded" placeholder="Branch name"
                                                value="{{ $branch->name ? $branch->name : '' }}" required>
                                            @error('branch_name')
                                                <span class="text-red-600">* {{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="grid md:grid-cols-3 py-2">
                                        Branch PIC
                                        <div class="col-span-2">
                                            @if ($branch->pic_branch->isNotEmpty())
                                                <ul class="list-disc">
                                                    @foreach ($branch->pic_branch as $user)
                                                        <li>{{ $user->name }} - {{ $user->email }}</li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <span class="italic">Not available</span>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- is_enabled --}}
                                    <div class="grid md:grid-cols-3 py-2">
                                        <label for="is_enabled" class="col-span-1 self-center">Status: <span
                                                class="text-red-600"></span></label>
                                        <div class="col-span-2">
                                            <div class="form-group">
                                                <div class="form-check mb-2">
                                                    <label class="form-check-label">
                                                        <input type="radio" class="form-check-input" value="1"
                                                            id="is_enabled_1" name="is_enabled" required
                                                            @if ($branch->is_enabled == 1) checked @endif>&nbsp;Active
                                                    </label>
                                                </div>
                                                <div class="form-check mb-2">
                                                    <label class="form-check-label">
                                                        <input type="radio" class="form-check-input" value="0"
                                                            id="is_enabled_2" name="is_enabled" required
                                                            @if ($branch->is_enabled == 0) checked @endif>&nbsp;Not
                                                        Active
                                                    </label>
                                                </div>
                                                @error('is_enabled')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

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
