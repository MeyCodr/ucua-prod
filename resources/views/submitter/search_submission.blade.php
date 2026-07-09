<x-guest-layout>
    @php
        Session::forget('staff_id');
    @endphp
    <x-auth-card>
        <div class="flex justify-between w-full px-8 mb-4">
            <img src="{{ asset('/img/phn-logo.png') }}" alt="PHN Logo" style="width: auto;">
            <img src="{{ asset('/img/zero_harm.png') }}" alt="Zero Harm Logo" style="width: 150px;height: auto">
        </div>
        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <div class="mb-5">
            Please enter your Staff ID. You may find it on your ticket submission and current points.
        </div>

        <form method="GET" id="searchForm" action="{{ route('SearchTicketResult', ['status' => 'Open']) }}">
            {{-- @csrf --}}
            <!-- Staff ID Input -->
            <div>
                <x-label for="staff_id" :value="__('Staff ID')" />
                <x-input id="staff_id" class="block mt-1 w-full" type="text" name="staff_id" :value="old('staff_id')"
                    required autofocus />
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-end mt-4">
                <a href="/" type="button"
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Back
                </a>
                <button type="submit" id="submitButton"
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                    <span id="buttonText">
                        <strong>Search</strong>
                    </span>
                    <span id="loadingSpinner" style="display: none;">
                        <i class="fa fa-spinner fa-spin"></i>
                        <strong>Searching...</strong>
                    </span>
                </button>
                <script>
                    // Disable button and show spinner
                    function disableSubmitButton() {
                        const submitButton = document.getElementById('submitButton');
                        const buttonText = document.getElementById('buttonText');
                        const loadingSpinner = document.getElementById('loadingSpinner');

                        // Disable button and show loading spinner
                        submitButton.disabled = true;
                        buttonText.style.display = "none";
                        loadingSpinner.style.display = "inline";

                        // Optional: Add overlay to prevent interactions
                        const overlay = document.createElement('div');
                        overlay.id = 'formOverlay';
                        document.body.appendChild(overlay);
                        overlay.style.display = 'block';
                    }

                    // reCAPTCHA callback
                    function onSubmit(token) {
                        // Submit form (button already disabled)
                        document.getElementById('searchForm').submit();
                    }

                    // Fallback if reCAPTCHA fails
                    document.getElementById('searchForm').addEventListener('submit', function() {
                        disableSubmitButton();
                    });
                </script>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
