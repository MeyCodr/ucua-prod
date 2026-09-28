<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3">
            @if ($pageTitle == 'New Division')
                <a href="{{ route('Division.index') }}"
                    class="inline-flex items-center gap-2 text-sm font-semibold text-gray-500 hover:text-gray-700 transition duration-150 ease-in-out flex-shrink-0" style="align-self: flex-start;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 12H5" /><path d="M12 19l-7-7 7-7" />
                    </svg>
                    Divisions
                </a>
            @else
                <a href="{{ route('Division.show', ['Division' => $division->id]) }}"
                    class="inline-flex items-center gap-2 text-sm font-semibold text-gray-500 hover:text-gray-700 transition duration-150 ease-in-out flex-shrink-0" style="align-self: flex-start;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 12H5" /><path d="M12 19l-7-7 7-7" />
                    </svg>
                    {{ $division->name }}
                </a>
            @endif
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-gray-900 leading-tight">
                    {{ $pageTitle }}
                </h2>
                <p class="mt-0.5 text-sm text-gray-500">
                    Fill in the division's name and appoint a head of division.
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
        .mb-5 { margin-bottom: 1.25rem; }
        .mb-6 { margin-bottom: 1.5rem; }
        .bg-indigo-50 { background-color: #eef2ff; }
        .text-indigo-600 { color: #4f46e5; }
        .bg-indigo-600 { background-color: #4f46e5; }
        .hover\:bg-indigo-700:hover { background-color: #4338ca; }
        .bg-red-50 { background-color: #fef2f2; }
        .text-red-600 { color: #dc2626; }
        .border-red-200 { border-color: #fecaca; }

        #division-form .card { background: #fff; border: 1px solid #e5e7eb; border-radius: 0.75rem; padding: 28px; box-shadow: 0 1px 2px rgba(20,20,19,0.04); max-width: 640px; }
        #division-form .card-head { display: flex; align-items: center; gap: 10px; margin-bottom: 22px; padding-bottom: 16px; border-bottom: 1px solid #f0efe9; }
        #division-form .card-icon { width: 32px; height: 32px; border-radius: 10px; background: #eef2ff; color: #4f46e5; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        #division-form .card-title { margin: 0; font-size: 13px; font-weight: 700; color: #14171a; text-transform: uppercase; letter-spacing: 0.04em; }
        #division-form label { display: block; font-size: 12.5px; font-weight: 600; color: #374151; margin-bottom: 6px; }
        #division-form input[type="text"],
        #division-form select {
            width: 100%;
            font-size: 13.5px;
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 0.625rem;
            background-color: #fff;
        }
        #division-form input[type="text"]:focus,
        #division-form select:focus {
            outline: none;
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.15);
        }
        #division-form .field { margin-bottom: 20px; }
        #division-form .field-error { font-size: 12px; color: #dc2626; margin-top: 6px; }
    </style>

    <div id="division-form" class="py-6">
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

                <div class="card">
                    <div class="card-head">
                        <div class="card-icon">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18" /><path d="M5 21V7l7-4 7 4v14" /><path d="M9 9h1" /><path d="M9 13h1" /><path d="M14 9h1" /><path d="M14 13h1" /></svg>
                        </div>
                        <h3 class="card-title">General Details</h3>
                    </div>

                    <div class="field">
                        <label for="name">Name <span class="text-red-600">*</span></label>
                        <input type="text" name="name" id="name"
                            placeholder="e.g. Operations"
                            value="{{ $formType == 'Edit' ? $division->name : old('name') }}"
                            required>
                        @error('name')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="field" style="margin-bottom: 0;">
                        <label for="user_head_id">Head of Division <span class="text-red-600">*</span></label>
                        <select name="user_head_id" id="user_head_id" required>
                            <option value="" hidden selected disabled>-- Please select one user --</option>
                            @forelse ($head_divs as $item)
                                @if ($formType == 'New')
                                    <option value="{{ $item->id }}"
                                        {{ old('user_head_id') == $item->id ? 'selected' : '' }}>
                                        {{ $item->name }}
                                    </option>
                                @else
                                    <option value="{{ $item->id }}"
                                        @if ($division->user_head_id == $item->id) selected @endif>
                                        {{ $item->name }}</option>
                                @endif
                            @empty
                                No Data
                            @endforelse
                            <option value="No Head" {{ old('user_head_id') == 'No Head' ? 'selected' : '' }}>
                                No Head Appointed
                            </option>
                        </select>
                        @error('user_head_id')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="flex items-center gap-3 mt-5" style="max-width: 640px;">
                    @if ($pageTitle == 'New Division')
                        <a href="{{ route('Division.index') }}"
                            class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-bold text-gray-700 hover:bg-gray-50 transition duration-150 ease-in-out">
                            Cancel
                        </a>
                    @else
                        <a href="{{ route('Division.show', ['Division' => $division->id]) }}"
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
