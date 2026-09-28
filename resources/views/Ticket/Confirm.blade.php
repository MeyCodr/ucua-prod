{{--
    The compiled public/css/app.css on this install predates this markup and can't
    currently be rebuilt (Laravel Mix's toolchain doesn't run on this machine), so a
    handful of Tailwind utilities below were never generated. These rules backfill
    exactly those classes with their standard Tailwind values, scoped to this component.
--}}
<style>
    .ticket-confirm .gap-2 { gap: 0.5rem; }
    .ticket-confirm .gap-3 { gap: 0.75rem; }
    .ticket-confirm .rounded-2xl { border-radius: 1rem; }
    .ticket-confirm .rounded-xl { border-radius: 0.75rem; }
    .ticket-confirm .w-14 { width: 3.5rem; }
    .ticket-confirm .h-14 { height: 3.5rem; }
    .ticket-confirm .bg-indigo-50 { background-color: #eef2ff; }
    .ticket-confirm .text-indigo-600 { color: #4f46e5; }
    .ticket-confirm .bg-indigo-600 { background-color: #4f46e5; }
    .ticket-confirm .hover\:bg-indigo-700:hover { background-color: #4338ca; }
    .ticket-confirm .bg-red-50 { background-color: #fef2f2; }
    .ticket-confirm .text-red-600 { color: #dc2626; }
    .ticket-confirm .bg-red-600 { background-color: #dc2626; }
    .ticket-confirm .hover\:bg-red-700:hover { background-color: #b91c1c; }
    .ticket-confirm .dropzone { border: 1.5px dashed #d9d5c6; border-radius: 0.75rem; padding: 16px; background: #f9f8f4; }
    .ticket-confirm .file-chip { display: inline-flex; align-items: center; gap: 6px; background: #fff; border: 1px solid #e5e7eb; border-radius: 999px; padding: 4px 10px; font-size: 12px; color: #33362e; margin: 4px 6px 0 0; }
</style>

<div class="ticket-confirm fixed z-10 inset-0 overflow-y-auto ConfirmModal" aria-labelledby="modal-title" role="dialog"
    aria-modal="true" hidden>
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div
            class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="submitForm" action="{{ route('SubmitApproverRespond') }}" method="post"
                enctype="multipart/form-data">
                @csrf
                <div style="padding: 32px 32px 8px; text-align: left;">
                    <div id="modalIconApprove" class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mb-5">
                        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5" /></svg>
                    </div>
                    <div id="modalIconDecline" class="w-14 h-14 rounded-2xl bg-red-50 text-red-600 flex items-center justify-center mb-5" hidden>
                        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6L6 18" /><path d="M6 6l12 12" /></svg>
                    </div>

                    <h3 class="font-bold text-gray-900" style="font-size: 19px; margin: 0 0 6px;" id="modalTitle">
                        Confirm Action
                    </h3>
                    <p class="text-gray-500" style="font-size: 13px; line-height: 1.5; margin: 0 0 20px;">
                        You are about to <span class="approverRespond"></span> this observation. Continue?
                    </p>

                    <input type="hidden" name="approverRespond" class="InputApproverRespond">
                    <input type="hidden" name="ticketId" value="{{ $ticket->id }}">
                    <input type="hidden" name="approverLevel" value="{{ $approvalStatues->approver_level }}">

                    <div class="mb-4">
                        <label for="remark" class="font-semibold text-gray-700" style="font-size: 12.5px;">Remarks (Optional)</label>
                        <textarea name="remark" id="remark" rows="4"
                            class="block mt-1 w-full rounded-xl border-gray-300"
                            style="font-size: 13.5px; padding: 10px 12px;"
                            placeholder="Enter here"></textarea>
                    </div>

                    <div class="attachmentUpload mb-4">
                        <label for="attachment" class="font-semibold text-gray-700" style="font-size: 12.5px;">Corrective Action (Required)</label>
                        <div class="text-gray-500 mb-2" style="font-size: 12px;">You may attach pictures as evidence.</div>

                        <div class="dropzone">
                            <input type="file" id="attachment" name="attachment[]" multiple
                                onchange="handleAttachment(event.target.files)" required
                                style="font-size: 12.5px;">
                            <div class="display_attachments"></div>
                            <div class="display_size_error"></div>
                            @error('attachment.*')
                                <div class="alert alert-danger" style="font-size: 12px; color: #dc2626; margin-top: 6px;">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2" style="padding: 16px 32px 28px; border-top: 1px solid #f0efe9;">
                    <button type="button"
                        class="inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white font-semibold text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        style="font-size: 13.5px;"
                        onclick="handleClickActionButton(false, '')">
                        Cancel
                    </button>
                    <button type="submit" id="submitButton"
                        class="bg-indigo-600 hover:bg-indigo-700 inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 font-bold text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        style="font-size: 13.5px;">
                        <span id="buttonText">Submit</span>
                        <span id="loadingSpinner" style="display: none;">
                            <i class="fa fa-spinner fa-spin"></i>
                            Submitting...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="{{ asset('jquery/3.4.1/jquery.min.js') }}"></script>
<script src="{{ asset('popper/popper.min.js') }}"></script>

<script>
    function handleAttachment(files) {
        $('.display_attachments').empty();
        $('.display_size_error').empty();
        var total_size = 0;
        [...files].forEach(function (each) {
            $('.display_attachments').append("<span class='file-chip'>" + each.name + "</span>");
            total_size += each.size;
        });

        if (total_size >= 10485760) { // if total file size exceed 10 MB
            $('.display_size_error').append("<div style='color:#dc2626;font-size:12px;margin-top:6px;'><b>Total File Size Exceeded Max (10 MB)</b></div>");
        }
    }

    function disableSubmitButton() {
        const submitButton = document.getElementById('submitButton');
        const buttonText = document.getElementById('buttonText');
        const loadingSpinner = document.getElementById('loadingSpinner');

        submitButton.disabled = true;
        buttonText.style.display = "none";
        loadingSpinner.style.display = "inline";
    }

    document.getElementById('submitForm').addEventListener('submit', function () {
        disableSubmitButton();
    });

    // reCAPTCHA callback
    function onSubmit(token) {
        document.getElementById('submitForm').submit();
    }

    function handleClickActionButton(status, respond) {
        if (!status) {
            $('.ConfirmModal').hide();
            return;
        }

        $('.ConfirmModal').show();
        $('.InputApproverRespond').val(respond);

        var actionText = "{{ $approveButtonText }}";
        var isDecline = respond === 'Declined';

        $('#modalIconApprove').toggle(!isDecline);
        $('#modalIconDecline').toggle(isDecline);
        $('#submitButton').toggleClass('bg-indigo-600', !isDecline).toggleClass('hover:bg-indigo-700', !isDecline);
        $('#submitButton').toggleClass('bg-red-600', isDecline).toggleClass('hover:bg-red-700', isDecline);

        if (respond === 'Verify') {
            $('#modalTitle').text('Confirm Verification');
            $('.approverRespond').html("<span style='color:#4f46e5;font-weight:700;'>" + actionText + "</span>");
            $('.attachmentUpload').show();
            $('#attachment').attr('required', true);
            $('#buttonText').text('Yes, Verify');
        } else if (respond === 'Complete') {
            $('#modalTitle').text('Confirm Completion');
            $('.approverRespond').html("<span style='color:#4f46e5;font-weight:700;'>" + actionText + "</span>");
            $('.attachmentUpload').hide();
            $('#attachment').removeAttr('required');
            $('#buttonText').text('Yes, Complete');
        } else if (respond === 'Declined') {
            $('#modalTitle').text('Confirm Decline');
            $('.approverRespond').html("<span style='color:#dc2626;font-weight:700;'>Decline</span>");
            $('.attachmentUpload').hide();
            $('#attachment').removeAttr('required');
            $('#buttonText').text('Yes, Decline');
        }
    }
</script>
