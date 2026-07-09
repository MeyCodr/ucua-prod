<div class="fixed z-10 inset-0 overflow-y-auto ConfirmModal" aria-labelledby="modal-title" role="dialog"
    aria-modal="true" hidden>
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div
            class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="submitForm" action="{{ route('SubmitApproverRespond') }}" method="post"
                enctype="multipart/form-data">
                @csrf
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 text-gray-900 font-bold" id="modal-title">
                                Confirmation
                            </h3>
                            <div class="mt-2">
                                <p class="text-gray-500">
                                    You are about to <span class="approverRespond"></span> this observation. Continue?
                                </p>
                            </div>
                            <div class="mt-2">
                                <input type="hidden" name="approverRespond" class="InputApproverRespond">
                                <input type="hidden" name="ticketId" value={{ $ticket->id }}>
                                <input type="hidden" name="approverLevel"
                                    value="{{ $approvalStatues->where('approver_status', 'Pending')->first()->approver_level }}">
                                <div>
                                    <label for="remark">Remarks (Optional)</label>
                                    <textarea name="remark" id="remark" cols="30" rows="5"
                                        class="block mt-1 w-full rounded-md border-gray-400"
                                        placeholder="Enter here"></textarea>
                                </div>
                                <div class="attachmentUpload mt-2">
                                    <div>
                                        <label for="attachment">Corrective Action (Required)</label>
                                        <div class="text-gray-500">You may attach pictures as evidence.</div>
                                    </div>
                                    <input type="file" id="attachment" name="attachment[]" multiple
                                        onchange="handleAttachment(event.target.files)" required>

                                    <div class="display_attachments"></div>
                                    <div class="display_size_error"></div>
                                    @error('attachment.*')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <script>
                                    function handleAttachment(files) {
                                        $('.display_attachments').empty();
                                        $('.display_size_error').empty();
                                        var total_size = 0;
                                        [...files].forEach(function (each) {
                                            $('.display_attachments').append("<div> - " + each.name + "</div>");
                                            total_size += each.size;
                                        });

                                        if (total_size >= 10485760) { // if total file size exceed 10 MB
                                            $('.display_size_error').append("<div style='color:red'><b>Total File Size Exceeded Max (10 MB)</b></div>");
                                        }

                                    }
                                </script>

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
                        function handleClickActionButton(respond) {
                            const form = document.getElementById('submitForm');
                            const inputRespond = document.querySelector('.InputApproverRespond');
                            const attachmentSection = document.querySelector('.attachmentUpload');
                            const approverText = document.querySelector('.approverRespond');

                            // Set hidden input value
                            inputRespond.value = respond;

                            if (respond === 'Verify') {
                                // Show modal for user to attach evidence if needed
                                $('.ConfirmModal').show();
                                approverText.innerHTML = "<span style='color:green;'>{{ $approveButtonText }}</span>";
                                attachmentSection.style.display = 'block';
                                document.getElementById('attachment').setAttribute('required', true);
                            } else if (respond === 'Complete') {
                                $('.ConfirmModal').show();
                                approverText.innerHTML = "<span style='color:green;'>{{ $approveButtonText }}</span>";
                                attachmentSection.style.display = 'none';
                                document.getElementById('attachment').removeAttribute('required');
                            } else if (respond === 'Declined') {
                                // No modal, submit directly
                                approverText.innerHTML = "<span style='color:red;'>Decline</span>";
                                attachmentSection.style.display = 'none';
                                document.getElementById('attachment').removeAttribute('required');

                                // Submit the form via POST
                                disableSubmitButton(); // optional: show spinner & disable
                                form.submit();
                            }
                        }

                        // Disable button and show loading spinner
                        function disableSubmitButton() {
                            const submitButton = document.getElementById('submitButton');
                            const buttonText = document.getElementById('buttonText');
                            const loadingSpinner = document.getElementById('loadingSpinner');

                            submitButton.disabled = true;
                            buttonText.style.display = "none";
                            loadingSpinner.style.display = "inline";
                        }

                        // Attach form submit handler to modal submit button
                        document.getElementById('submitForm').addEventListener('submit', function () {
                            disableSubmitButton();
                        });

                        // reCAPTCHA callback
                        function onSubmit(token) {
                            // Submit form (button already disabled)
                            document.getElementById('submitForm').submit();
                        }

                        // Fallback if reCAPTCHA fails
                        document.getElementById('submitForm').addEventListener('submit', function () {
                            disableSubmitButton();
                        });
                    </script>
                    <button type="button"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                        onclick="handleClickActionButton('Declined')">
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
    $(document).ready(function () {
        // $('.ConfirmModal').hide();
    });

    function handleClickActionButton(status, respond) {
        var actionText = "{{ $approveButtonText }}";
        if (status) {
            $('.ConfirmModal').show();
        } else {
            $('.ConfirmModal').hide();
        }

        $('.InputApproverRespond').val(respond);

        if (respond == "Verify") {
            $('.approverRespond').html("<span style='color:green;'>" + actionText + "</span>");
            $('.attachmentUpload').show();
            // Make attachment required
            $('#attachment').attr('required', true);
        } else if (respond == "Complete") {
            $('.approverRespond').html("<span style='color:green;'>" + actionText + "</span>");
            $('.attachmentUpload').hide();
            // Make attachment not required
            $('#attachment').removeAttr('required');
        } else if (respond == "Declined") {
            $('.approverRespond').html("<span style='color:red;'>Decline</span>");
            $('.attachmentUpload').hide();
            // Make attachment not required
            $('#attachment').removeAttr('required');
        }
    }
</script>
