<div class="fixed z-10 inset-0 overflow-y-auto ConfirmModal" aria-labelledby="modal-title" role="dialog" aria-modal="true"
    hidden>
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div
            class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="submitForm"
                action="{{ route('admin.redeem.approve', ['status' => $status, 'staff_id' => $staff_id, 'redeem_id' => $redeem->id]) }}"
                method="post" enctype="multipart/form-data">
                @csrf
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 font-bold" id="modal-title">
                                Confirmation
                            </h3>
                            <div class="mt-2">
                                <p class="text-gray-500">
                                    Are you want proceed to <span style="color:green;">APPROVE</span> this request?
                                </p>
                                <div class="mt-4 p-4 border border-gray-300 rounded bg-gray-50">
                                    <div><strong>{{ $submitter->name }}</strong></div>
                                    <div><strong>{{ $submitter->email }}</strong></div>
                                    <div>{{ $submitter->phone_number }}</div>
                                    <div class="text-gray-600 mt-2">Point to Redeem</div>
                                    <div class="text-red">
                                        <strong>{{ $redeem->points }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" id="submitButton"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                        <span id="buttonText">
                            <strong>Yes, Approve</strong>
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
    function handleClickActionButton(status, action) {
        if (status) {
            $('.ConfirmModal').show();
        } else {
            $('.ConfirmModal').hide();
        }
    }
</script>
