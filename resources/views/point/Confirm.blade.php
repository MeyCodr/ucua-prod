@php
    $initials = function (?string $name) {
        $parts = array_filter(preg_split('/\s+/', trim((string) $name)));
        $letters = array_map(fn ($p) => mb_substr($p, 0, 1), array_slice($parts, 0, 2));
        return mb_strtoupper(implode('', $letters)) ?: '—';
    };
@endphp

{{--
    The compiled public/css/app.css on this install predates this markup and can't
    currently be rebuilt (Laravel Mix's toolchain doesn't run on this machine), so a
    handful of Tailwind utilities below were never generated. These rules backfill
    exactly those classes with their standard Tailwind values, scoped to this component.
--}}
<style>
    .redeem-confirm .gap-2 { gap: 0.5rem; }
    .redeem-confirm .gap-3 { gap: 0.75rem; }
    .redeem-confirm .w-11 { width: 2.75rem; }
    .redeem-confirm .h-11 { height: 2.75rem; }
    .redeem-confirm .w-14 { width: 3.5rem; }
    .redeem-confirm .h-14 { height: 3.5rem; }
    .redeem-confirm .rounded-2xl { border-radius: 1rem; }
    .redeem-confirm .rounded-xl { border-radius: 0.75rem; }
    .redeem-confirm .bg-indigo-50 { background-color: #eef2ff; }
    .redeem-confirm .text-indigo-600 { color: #4f46e5; }
    .redeem-confirm .text-indigo-700 { color: #4338ca; }
    .redeem-confirm .bg-indigo-600 { background-color: #4f46e5; }
    .redeem-confirm .hover\:bg-indigo-700:hover { background-color: #4338ca; }
</style>

<div class="redeem-confirm fixed z-10 inset-0 overflow-y-auto ConfirmModal" aria-labelledby="modal-title" role="dialog" aria-modal="true"
    hidden>
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div
            class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full">
            <form id="submitForm"
                action="{{ route('admin.redeem.approve', ['status' => $status, 'staff_id' => $staff_id, 'redeem_id' => $redeem->id]) }}"
                method="post" enctype="multipart/form-data">
                @csrf
                <div style="padding: 32px 32px 24px; text-align: left;">
                    <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mb-5">
                        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5" /></svg>
                    </div>

                    <h3 class="font-bold text-gray-900" style="font-size: 19px; margin: 0 0 6px;" id="modal-title">
                        Confirm Approval
                    </h3>
                    <p class="text-gray-500" style="font-size: 13px; line-height: 1.5; margin: 0 0 22px;">
                        You're about to approve this point redemption request. The requester will be notified by email.
                    </p>

                    <div class="rounded-xl" style="background: #f6f5f0; padding: 18px 20px;">
                        <div class="flex items-center gap-3">
                            <span class="w-11 h-11 rounded-full bg-white text-indigo-700 flex items-center justify-center flex-shrink-0" style="font-size: 13px; font-weight: 700; box-shadow: 0 1px 2px rgba(20,20,19,0.06);">
                                {{ $initials($submitter->name) }}
                            </span>
                            <div>
                                <div class="font-bold text-gray-900" style="font-size: 13.5px;">{{ $submitter->name }}</div>
                                <div class="text-gray-500" style="font-size: 12px; margin-top: 1px;">{{ $submitter->email }}</div>
                                <div class="text-gray-500" style="font-size: 12px;">{{ $submitter->phone_number }}</div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between" style="margin-top: 16px; padding-top: 16px; border-top: 1px solid #e8e5db;">
                            <span class="font-semibold text-gray-500" style="font-size: 12px; text-transform: uppercase; letter-spacing: 0.05em;">Points to Redeem</span>
                            <span class="font-bold text-gray-900" style="font-size: 20px;">{{ $redeem->points }}</span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2" style="padding: 16px 32px 28px; border-top: 1px solid #f0efe9;">
                    <button type="button"
                        class="inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white font-semibold text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        style="font-size: 13.5px;"
                        onclick="handleClickActionButton(false,'')">
                        Cancel
                    </button>
                    <button type="submit" id="submitButton"
                        class="inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-indigo-600 font-bold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        style="font-size: 13.5px;">
                        <span id="buttonText">Yes, Approve</span>
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
    function handleClickActionButton(status, action) {
        if (status) {
            $('.ConfirmModal').show();
        } else {
            $('.ConfirmModal').hide();
        }
    }

    // Disable button and show spinner
    function disableSubmitButton() {
        const submitButton = document.getElementById('submitButton');
        const buttonText = document.getElementById('buttonText');
        const loadingSpinner = document.getElementById('loadingSpinner');

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

    document.getElementById('submitForm').addEventListener('submit', function () {
        disableSubmitButton();
    });
</script>
