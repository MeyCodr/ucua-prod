{{--
    The compiled public/css/app.css on this install predates this markup and can't currently be
    rebuilt (Laravel Mix's toolchain doesn't run on this machine), so the modal carries its own
    scoped styles. Note: no `display` is set on .ConfirmModal itself, because jQuery's show()/hide()
    in handleClickActionButton() toggles it together with the `hidden` attribute.
--}}
<style>
    .ConfirmModal { position: fixed; top: 0; right: 0; bottom: 0; left: 0; z-index: 50; overflow-y: auto; background: rgba(17, 24, 39, 0.55); }
    .ConfirmModal .rm-center { display: flex; align-items: center; justify-content: center; min-height: 100%; padding: 16px; }
    .ConfirmModal .rm-dialog { width: 100%; max-width: 30rem; background: #fff; border-radius: 0.9rem; box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3); overflow: hidden; text-align: left; }
    .ConfirmModal .rm-body { padding: 24px 24px 8px; }
    .ConfirmModal .rm-title { margin: 0; font-size: 18px; font-weight: 700; color: #111827; }
    .ConfirmModal .rm-text { margin: 8px 0 0; font-size: 13px; line-height: 1.55; color: #6b7280; }
    .ConfirmModal .rm-text b { color: #111827; }
    .ConfirmModal .rm-label { display: block; margin: 18px 0 6px; font-size: 13px; font-weight: 600; color: #111827; }
    .ConfirmModal select { width: 100%; padding: 10px 12px; font-size: 14px; color: #111827; background: #fff; border: 1px solid #d1d5db; border-radius: 0.5rem; }
    .ConfirmModal select:focus { outline: 2px solid #16a34a; outline-offset: 0; border-color: #16a34a; }
    .ConfirmModal .rm-footer { display: flex; flex-direction: row-reverse; gap: 10px; padding: 18px 24px 24px; }
    .ConfirmModal .rm-btn { flex: 1; padding: 11px 16px; font-size: 14px; font-weight: 700; border-radius: 0.5rem; cursor: pointer; }
    .ConfirmModal .rm-btn-primary { color: #fff; background: #16a34a; border: 0; }
    .ConfirmModal .rm-btn-primary:hover { background: #15803d; }
    .ConfirmModal .rm-btn-primary:disabled { opacity: 0.7; cursor: default; }
    .ConfirmModal .rm-btn-cancel { color: #374151; background: #fff; border: 1px solid #d1d5db; }
    .ConfirmModal .rm-btn-cancel:hover { background: #f9fafb; }
    #formOverlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 60; background: rgba(32, 32, 32, 0.4); display: none; }
</style>

<div class="ConfirmModal" aria-labelledby="modal-title" role="dialog" aria-modal="true" hidden>
    <div class="rm-center">
        <div class="rm-dialog">
            <form id="submitForm" action="{{ route('redeem.point.submit', ['staff_id' => $staff_id]) }}" method="post"
                enctype="multipart/form-data">
                @csrf
                <div class="rm-body">
                    <h3 class="rm-title" id="modal-title">Confirm redemption</h3>
                    <p class="rm-text">
                        Kindly confirm to redeem your points by selecting the points to redeem below.
                        You can only redeem up to <b>{{ $point_balance }} points</b>.
                    </p>

                    <input type="hidden" name="staff_id" value="{{ $staff_id }}">
                    <label for="points" class="rm-label">Points to redeem</label>
                    {{-- box to select 10, 50 or 100 points to redeem. check the current point, disable options if not enough points --}}
                    <select id="points" name="points" required>
                        <option value="" disabled selected>Select points to redeem</option>
                        <option value="10" @if ($point_balance < 10) disabled @endif>10 points - RM20 Coupon/Voucher</option>
                        <option value="50" @if ($point_balance < 50) disabled @endif>50 points - RM100 Coupon/Voucher</option>
                        <option value="100" @if ($point_balance < 100) disabled @endif>100 points - RM200 Coupon/Voucher</option>
                    </select>
                </div>
                <div class="rm-footer">
                    <button type="submit" id="submitButton" class="rm-btn rm-btn-primary">
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
                    <button type="button" class="rm-btn rm-btn-cancel" onclick="handleClickActionButton(false,'')">
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

    // Convenience: clicking the dark backdrop or pressing Escape closes the dialog.
    $('.ConfirmModal').on('click', function (e) {
        if ($(e.target).is('.ConfirmModal, .rm-center')) handleClickActionButton(false);
    });
    $(document).on('keydown', function (e) {
        if (e.key === 'Escape') handleClickActionButton(false);
    });
</script>
