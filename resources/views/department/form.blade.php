<x-app-layout>
    <x-slot name="header">
        <div class="grid md:grid-cols-2">
            <div class="flex justify-start">
                <div class="inline-block mr-20">
                    @if ($pageTitle == 'New Department')
                        <a href="{{ route('Division.show', ['Division' => $div_id]) }}">Back</a>
                    @else
                        <a href="{{ route('Department.show', ['div_id' => $div_id, 'Department' => $department->id]) }}">Back</a>
                    @endif
                </div>
                <div>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ $pageTitle }}
                    </h2>
                </div>
            </div>
        </div>
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
                                                value="{{ $formType == 'Edit' ? $department->name : old('name') }}"
                                                required>
                                            @error('name')
                                                <span class="text-red-600">* {{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="grid md:grid-cols-3 py-2">
                                        <label for="short_name" class="col-span-1 self-center">Short Name: <span
                                                class="text-red-600">*</span></label>
                                        <div class="col-span-2">
                                            <input type="text" name="short_name" id="short_name" class="w-full rounded"
                                                placeholder="Short Name"
                                                value="{{ $formType == 'Edit' ? $department->short_name : old('short_name') }}"
                                                required>
                                            @error('short_name')
                                                <span class="text-red-600">* {{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="grid md:grid-cols-3 py-2">
                                        <label for="user_head_id" class="col-span-1 self-center">Head of Department: <span
                                                class="text-red-600">*</span></label>
                                        <select name="user_head_id" id="user_head_id" class="col-span-2 w-full rounded"
                                            required>
                                            <option value="" hidden selected disabled>-- Please select one user --
                                            </option>
                                            @forelse ($head_departments as $item)
                                                @if ($formType == 'New')
                                                    <option value="{{ $item->id }}"
                                                        {{ old('user_head_id') == $item->id ? 'selected' : '' }}>
                                                        {{ $item->name }}
                                                    </option>
                                                @else
                                                    <option value="{{ $item->id }}"
                                                        @if ($department->user_head_id == $item->id) selected @endif>
                                                        {{ $item->name }}</option>
                                                @endif
                                            @empty
                                                No Data
                                            @endforelse
                                            <option value="No Head"
                                                {{ old('user_head_id') == 'No Head' ? 'selected' : '' }}>No Head
                                                Appointed</option>
                                        </select>
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
