<div class="fixed z-10 inset-0 overflow-y-auto ConfirmModal" aria-labelledby="modal-title" role="dialog" aria-modal="true"
    hidden>
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div
            class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="submitForm" action="{{ route('redeem.point.submit', ['staff_id' => $staff_id]) }}" method="post"
                enctype="multipart/form-data">
                @csrf
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 font-bold" id="modal-title">
                                Confirmation
                            </h3>
                            <div class="mt-2">
                                <p class="text-gray-500">
                                    Kindly confirm to redeem your points by selecting the point to redeem below. Glad to remind you can only redeem points up to {{ $point_balance }} points.
                                </p>
                            </div>
                            <div class="mt-2">
                                <input type="hidden" name="staff_id" value="{{ $staff_id }}">
                                <div>
                                    <label for="points">Point</label>
                                    {{-- box to select 10, 50 or 100 points to redeem. check the current point, disable options if not enough points --}}

                                    <select id="points" name="points" required
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm">
                                        <option value="" disabled selected>Select points to redeem</option>
                                        <option value="10" @if ($point_balance < 10) disabled @endif>10 points - RM20 Coupon/Voucher</option>
                                        <option value="50" @if ($point_balance < 50) disabled @endif>50 points - RM100 Coupon/Voucher</option>
                                        <option value="100" @if ($point_balance < 100) disabled @endif>100 points - RM200 Coupon/Voucher</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" id="submitButton"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                        <span id="buttonText">
                            <strong>Submit</strong>
                        </span>
                        <span id="loadingSpinner" style="display: none;">
                            <i class="fa fa-spinner fa-spin"></i>
                            <strong>Submitting...</strong>
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
                            document.getElementById('submitForm').submit();
                        }

                        // Fallback if reCAPTCHA fails
                        document.getElementById('submitForm').addEventListener('submit', function() {
                            disableSubmitButton();
                        });
                    </script>
                    <button type="button"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                        onclick="handleClickActionButton(false,'')">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="{{ asset('jquery/3.4.1/jquery.min.js') }}"></script>
<script src="{{ asset('popper/popper.min.js') }}"></script>

<script>
    function handleClickActionButton(status) {
        if (status) {
            $('.ConfirmModal').show();
        } else {
            $('.ConfirmModal').hide();
        }
    }
</script>
