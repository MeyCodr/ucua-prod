<x-guest-layout>

    @component('Style')
    @endcomponent

    <style>
        /* Plain, readable form: system font, one column, big labels and tall inputs.
           Colour is used sparingly: orange only for required marks, focus and the submit button. */
        .ucua-shell { padding: 32px 16px 56px; }
        .ucua-sheet { max-width: 960px; margin: 0 auto; background: #FFFFFF; color: #1F2933; border-radius: 4px; box-shadow: 0 2px 10px rgba(0,0,0,0.25); font-family: 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; font-size: 14px; line-height: 1.5; overflow: hidden; overflow: clip; /* clip (not hidden) so the sticky step nav can stick */ }
        .ucua-sheet * { box-sizing: border-box; }

        .ucua-header { display: flex; align-items: center; justify-content: space-between; padding: 20px 32px; border-bottom: 1px solid #E2E2E2; }
        .ucua-logo-phn { height: 24px; width: auto; }
        .ucua-logo-zh { height: 30px; width: auto; }
        .ucua-header-right { display: flex; align-items: center; gap: 20px; }
        .ucua-btn-ghost { color: #1F2933; font-size: 14px; text-decoration: underline; padding: 4px 0; }
        .ucua-btn-ghost:hover { color: #FC471A; }

        .ucua-alerts { padding: 0 32px; }
        .ucua-alerts .alert-danger { border-radius: 4px; font-size: 14px; padding: 10px 14px; }

        .ucua-intro { margin: 0 32px; padding: 20px 0; border-bottom: 1px solid #E2E2E2; text-align: left; }
        .ucua-intro-title { font-weight: 600; font-size: 15px; line-height: 1.45; color: #1F2933; }
        .ucua-intro-my { font-weight: 400; font-size: 13px; color: #6B7280; }

        .ucua-layout { display: grid; grid-template-columns: 190px 1fr; align-items: start; }
        .ucua-aside { padding: 24px 12px 40px 32px; position: sticky; top: 16px; }
        .ucua-main { min-width: 0; padding: 8px 32px 48px 24px; max-width: 720px; }

        /* Step list: plain numbered text, the current one is bold with an orange bar. */
        .ucua-step-item { display: flex; align-items: center; gap: 10px; padding: 7px 0 7px 10px; border-left: 3px solid #E2E2E2; cursor: pointer; }
        .ucua-step-item--active { border-left-color: #FC471A; }
        .ucua-step-line { display: none; }
        .ucua-step-dot { font-size: 13px; font-weight: 600; color: #9AA1AB; width: 14px; flex-shrink: 0; }
        .ucua-step-dot--active { color: #FC471A; }
        .ucua-step-name { font-size: 13px; color: #6B7280; }
        .ucua-step-item:hover .ucua-step-name { color: #1F2933; }
        .ucua-step-item--active .ucua-step-name { color: #1F2933; font-weight: 600; }

        .ucua-section { padding: 28px 0; border-top: 1px solid #E2E2E2; }
        .ucua-section:first-child { border-top: none; padding-top: 20px; }
        .ucua-section-header { display: flex; align-items: flex-start; gap: 10px; margin-bottom: 18px; }
        .ucua-section-num { font-size: 17px; font-weight: 700; line-height: 1.3; color: #FC471A; flex-shrink: 0; }
        .ucua-section-title { font-size: 17px; font-weight: 700; line-height: 1.3; color: #1F2933; margin: 0; }
        .ucua-section-subtitle { font-size: 12.5px; color: #6B7280; margin-top: 2px; }

        .ucua-field { margin-bottom: 20px; }
        .ucua-field:last-child { margin-bottom: 0; }
        .ucua-label { display: block; font-weight: 600; font-size: 14px; color: #1F2933; margin-bottom: 0; }
        .ucua-label-my { display: block; font-size: 12px; color: #6B7280; margin-bottom: 6px; }
        .ucua-required { color: #D92D0A; margin-left: 3px; }
        .ucua-input, .ucua-select, .ucua-textarea { width: 100%; min-height: 40px; border: 1px solid #9AA1AB; border-radius: 4px; padding: 8px 12px; font-size: 14px; font-family: inherit; color: #1F2933; background: #FFFFFF; }
        .ucua-input, .ucua-select { height: 40px; }
        .ucua-input::placeholder, .ucua-textarea::placeholder { color: #9AA1AB; }
        .ucua-input:focus, .ucua-select:focus, .ucua-textarea:focus { outline: 2px solid #FC471A; outline-offset: 0; border-color: #FC471A; }
        .ucua-textarea { resize: vertical; line-height: 1.5; }
        .ucua-select { appearance: none; background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%236B7280' stroke-width='2'><polyline points='6 9 12 15 18 9'/></svg>"); background-repeat: no-repeat; background-position: right 12px center; padding-right: 38px; }
        .ucua-helper { font-size: 13px; color: #4B5563; line-height: 1.6; margin-bottom: 8px; }
        .ucua-helper b { color: #1F2933; }
        .ucua-grid-2 { display: grid; grid-template-columns: 1fr; gap: 0; }
        .ucua-grid-2 > .ucua-field { margin-bottom: 20px; }

        .ucua-radio-row { display: flex; align-items: flex-start; gap: 12px; padding: 12px 14px; border: 1px solid #C9CDD3; border-radius: 4px; margin-bottom: 8px; background: #FFFFFF; cursor: pointer; }
        .ucua-radio-row:hover { background: #F7F7F8; }
        .ucua-radio-row:has(.ucua-radio-input:checked) { border-color: #FC471A; background: #FFF6F2; }
        .ucua-radio-card { display: flex; align-items: flex-start; gap: 12px; padding: 14px 16px; border: 1px solid #C9CDD3; border-radius: 4px; margin-bottom: 8px; background: #FFFFFF; cursor: pointer; }
        .ucua-radio-card:hover { background: #F7F7F8; }
        .ucua-radio-card:has(.ucua-radio-input:checked) { border-color: #FC471A; background: #FFF6F2; }
        .ucua-radio-input { margin-top: 3px; width: 18px; height: 18px; accent-color: #FC471A; flex-shrink: 0; cursor: pointer; }
        .ucua-radio-text { font-size: 14px; font-weight: 600; color: #1F2933; }
        .ucua-radio-text-plain { font-size: 14px; color: #1F2933; }
        .ucua-radio-text-my { font-size: 12px; color: #6B7280; margin-top: 1px; }
        .ucua-checkbox-grid { display: grid; grid-template-columns: repeat(3, minmax(0,1fr)); gap: 8px; }
        .ucua-checkbox-chip { display: flex; align-items: center; gap: 10px; padding: 11px 13px; border: 1px solid #C9CDD3; border-radius: 4px; background: #FFFFFF; cursor: pointer; }
        .ucua-checkbox-chip:has(.ucua-checkbox-input:checked) { border-color: #FC471A; background: #FFF6F2; }
        .ucua-checkbox-input { width: 18px; height: 18px; accent-color: #FC471A; cursor: pointer; }
        .ucua-upload-box { border: 1px solid #C9CDD3; border-radius: 4px; padding: 14px; background: #F7F7F8; }
        .ucua-upload-head { display: flex; align-items: center; gap: 8px; margin-bottom: 8px; }
        .ucua-file-list { font-size: 13px; color: #4B5563; margin-top: 6px; }

        .ucua-btn-primary { display: inline-flex; align-items: center; justify-content: center; gap: 8px; background: #FC471A; color: #FFFFFF; border: 0; border-radius: 4px; padding: 14px 28px; font-family: inherit; font-weight: 600; font-size: 15px; cursor: pointer; width: 100%; }
        .ucua-btn-primary:hover { color: #FFFFFF; background: #E23D12; }

        .ucua-radio-text, .ucua-radio-text-plain, .ucua-radio-text-my, .ucua-section-title, .ucua-section-subtitle, .ucua-file-list { overflow-wrap: anywhere; }
        .ucua-radio-row > div, .ucua-radio-card > div { min-width: 0; }
        .ucua-section { scroll-margin-top: 16px; }

        @media (max-width: 860px) {
            .ucua-shell { padding: 0 0 40px; margin: 0 -15px; }
            .ucua-sheet { border-radius: 0; box-shadow: none; }
            .ucua-header { padding: 14px 20px; gap: 12px; }
            .ucua-header-right { gap: 14px; }
            .ucua-layout { display: block; }

            /* Step list becomes a scrolling strip pinned to the top while scrolling. */
            .ucua-aside { position: sticky; top: 0; z-index: 10; padding: 0; background: #FFFFFF; border-bottom: 1px solid #E2E2E2; }
            .ucua-aside > div { display: flex; overflow-x: auto; gap: 4px; padding: 0 20px; scrollbar-width: none; -webkit-overflow-scrolling: touch; }
            .ucua-aside > div::-webkit-scrollbar { display: none; }
            .ucua-step-item { flex-shrink: 0; padding: 12px 10px; gap: 6px; border-left: 0; border-bottom: 3px solid transparent; }
            .ucua-step-item--active { border-left-color: transparent; border-bottom-color: #FC471A; }
            .ucua-step-dot { width: auto; }
            .ucua-step-name { white-space: nowrap; }

            .ucua-section { scroll-margin-top: 56px; padding: 24px 0; }
            .ucua-main { padding: 4px 20px 40px; max-width: none; }
            .ucua-checkbox-grid { grid-template-columns: repeat(2, minmax(0,1fr)); }
            .ucua-alerts { padding: 0 20px; }
            .ucua-intro { margin: 0 20px; padding: 16px 0; }
            /* 16px inputs stop iOS Safari from zooming the page on focus. */
            .ucua-input, .ucua-select, .ucua-textarea { font-size: 16px; min-height: 44px; padding: 10px 12px; }
            .ucua-input, .ucua-select { height: 44px; }
            .ucua-btn-primary { max-width: none; }
        }

        @media (max-width: 480px) {
            .ucua-logo-phn { height: 20px; }
            .ucua-logo-zh { height: 26px; }
            .ucua-intro-title { font-size: 14.5px; }
            .ucua-section-title, .ucua-section-num { font-size: 16px; }
        }
    </style>

    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    <script src="{{ asset('js/image-compress.js') }}"></script>

    <script>
        // Step nav: highlight the section currently in view; click a step to scroll to it.
        document.addEventListener('DOMContentLoaded', function () {
            var navItems = document.querySelectorAll('[data-step-nav]');
            var sections = document.querySelectorAll('.ucua-section[data-step]');

            // Section 4 has two variants (unsafe condition / unsafe act); only the visible one counts.
            function visibleSections() {
                return Array.prototype.filter.call(sections, function (s) { return s.offsetParent !== null; });
            }

            var lastStep = null;

            function setActive(step) {
                var activeItem = null;
                navItems.forEach(function (item) {
                    var on = item.getAttribute('data-step-nav') === String(step);
                    if (on) activeItem = item;
                    item.classList.toggle('ucua-step-item--active', on);
                    item.querySelector('.ucua-step-dot').classList.toggle('ucua-step-dot--active', on);
                });

                // On mobile the nav is a horizontal strip: keep the active step visible inside it.
                // (Scrolls the strip itself; scrollIntoView would also scroll the page.)
                if (step === lastStep || !activeItem) return;
                lastStep = step;
                var strip = activeItem.parentElement;
                if (strip.scrollWidth > strip.clientWidth) {
                    strip.scrollTo({ left: activeItem.offsetLeft - (strip.clientWidth - activeItem.offsetWidth) / 2, behavior: 'smooth' });
                }
            }

            function update() {
                var list = visibleSections();
                if (!list.length) return;
                var current = list[0];
                var probe = window.innerHeight * 0.35;
                list.forEach(function (s) {
                    if (s.getBoundingClientRect().top <= probe) current = s;
                });
                // At the very bottom the last section may never reach the probe line.
                if (window.innerHeight + window.scrollY >= document.documentElement.scrollHeight - 2) {
                    current = list[list.length - 1];
                }
                setActive(current.getAttribute('data-step'));
            }


            navItems.forEach(function (item) {
                item.addEventListener('click', function () {
                    var step = item.getAttribute('data-step-nav');
                    var target = visibleSections().filter(function (s) { return s.getAttribute('data-step') === step; })[0];
                    if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                });
            });

            window.addEventListener('scroll', update, { passive: true });
            window.addEventListener('resize', update);
            update();
        });
    </script>

    <div class="ucua-shell">
        <div class="ucua-sheet">

            {{-- Header --}}
            <div class="ucua-header">
                <img src="{{ asset('/img/phn-logo.png') }}" alt="PHN Logo" class="ucua-logo-phn">
                <div class="ucua-header-right">
                    <img src="{{ asset('/img/zero_harm.png') }}" alt="Zero Harm Logo" class="ucua-logo-zh">
                    <a href="{{ url('/') }}" class="ucua-btn-ghost">Home</a>
                </div>
            </div>

            {{-- Session Status --}}
            <div class="ucua-alerts">
                <x-auth-session-status class="mb-4" :status="session('status')" />

                {{-- Validation Errors --}}
                <x-auth-validation-errors class="mb-4" :errors="$errors" />
            </div>

            {{-- Intro --}}
            <div class="ucua-intro">
                <div class="ucua-intro-title">You are a step away to make the workplace a safer place!<br>
                    <span class="ucua-intro-my">Anda hanya tinggal selangkah sahaja lagi untuk memastikan tempat kerja yang lebih selamat!</span>
                </div>
                <div class="ucua-intro-title" style="margin-top: 10px; font-size: 13.5px;">Report your observation here!<br>
                    <span class="ucua-intro-my">Laporkan pemerhatian anda di sini!</span>
                </div>
            </div>

            <form action="{{ route('SubmitNewTicket') }}" method="post" enctype="multipart/form-data" id="newTicketForm">
                @csrf
                <div class="ucua-layout">

                    {{-- Sticky step nav --}}
                    <div class="ucua-aside">
                        <div>
                            <div class="ucua-step-item" data-step-nav="1">
                                <div class="ucua-step-line"></div>
                                <div class="ucua-step-dot ucua-step-dot--active">1</div>
                                <div class="ucua-step-name">My Details</div>
                            </div>
                            <div class="ucua-step-item" data-step-nav="2">
                                <div class="ucua-step-line"></div>
                                <div class="ucua-step-dot">2</div>
                                <div class="ucua-step-name">Location</div>
                            </div>
                            <div class="ucua-step-item" data-step-nav="3">
                                <div class="ucua-step-line"></div>
                                <div class="ucua-step-dot">3</div>
                                <div class="ucua-step-name">Type</div>
                            </div>
                            <div class="ucua-step-item" data-step-nav="4">
                                <div class="ucua-step-line"></div>
                                <div class="ucua-step-dot">4</div>
                                <div class="ucua-step-name">Observation</div>
                            </div>
                            <div class="ucua-step-item" data-step-nav="5">
                                <div class="ucua-step-line"></div>
                                <div class="ucua-step-dot">5</div>
                                <div class="ucua-step-name">Explanation</div>
                            </div>
                            <div class="ucua-step-item" data-step-nav="6">
                                <div class="ucua-step-line"></div>
                                <div class="ucua-step-dot">6</div>
                                <div class="ucua-step-name">Documents</div>
                            </div>
                            <div class="ucua-step-item" data-step-nav="7">
                                <div class="ucua-step-dot">7</div>
                                <div class="ucua-step-name">My Action</div>
                            </div>
                        </div>
                    </div>

                    <div class="ucua-main">

                        {{-- Section 1 Contact Detail --}}
                        <div class="ucua-section" data-step="1">
                            <div class="ucua-section-header">
                                <div class="ucua-section-num">01</div>
                                <div>
                                    <h2 class="ucua-section-title">My Details</h2>
                                    <div class="ucua-section-subtitle">Butiran Saya</div>
                                </div>
                            </div>

                            <div class="ucua-grid-2">
                                {{-- Full name --}}
                                <div class="ucua-field">
                                    <label for="name" class="ucua-label">My Name<span class="ucua-required">*</span></label>
                                    <label for="name" class="ucua-label-my">Nama Saya</label>
                                    <input type="text" class="ucua-input" placeholder="Enter here" id="name"
                                        name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Email Address --}}
                                <div class="ucua-field">
                                    <label for="email" class="ucua-label">My Email Address<span class="ucua-required">*</span></label>
                                    <label for="email" class="ucua-label-my">Alamat Email Saya</label>
                                    <input type="email" class="ucua-input" placeholder="Enter here" id="email"
                                        name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Staff ID --}}
                                <div class="ucua-field">
                                    <label for="staff_id" class="ucua-label">My Staff ID<span class="ucua-required">*</span></label>
                                    <label for="staff_id" class="ucua-label-my">ID Staf Saya</label>
                                    <input type="text" class="ucua-input" placeholder="e.g. 0123456789" id="staff_id"
                                        name="staff_id" value="{{ old('staff_id') }}" required>
                                    @error('staff_id')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Phone No. --}}
                                <div class="ucua-field">
                                    <label for="phone_number" class="ucua-label">My Phone No.<span class="ucua-required">*</span></label>
                                    <label for="phone_number" class="ucua-label-my">No. Telefon Saya</label>
                                    <input type="text" class="ucua-input" placeholder="Enter here" id="phone_number"
                                        name="phone_number" value="{{ old('phone_number') }}" required>
                                    @error('phone_number')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Department Name --}}
                                <div class="ucua-field">
                                    <label for="department_id" class="ucua-label">My Department<span class="ucua-required">*</span></label>
                                    <label for="department_id" class="ucua-label-my">Jabatan Saya</label>
                                    <select name="department_id" id="department_id" class="ucua-select" required>
                                        <option hidden selected value="">Please choose</option>
                                        @foreach ($departments as $dept)
                                            @if ($dept->subdepartment->count())
                                                <optgroup label="{{ $dept->name }}">
                                                    @foreach ($dept->subdepartment as $sub)
                                                        <option value="sub_{{ $sub->id }}"
                                                            {{ old('department_id') == 'sub_' . $sub->id ? 'selected' : '' }}>
                                                            {{ $sub->name }}
                                                        </option>
                                                    @endforeach
                                                </optgroup>
                                            @else
                                                <option value="dept_{{ $dept->id }}"
                                                    {{ old('department_id') == 'dept_' . $dept->id ? 'selected' : '' }}>
                                                    {{ $dept->name }}
                                                </option>
                                            @endif
                                        @endforeach
                                        <option value="0" {{ old('department_id') == '0' ? 'selected' : '' }}>Others</option>
                                    </select>
                                    <div id="other-department-input" class="ucua-field" style="margin-top: 10px; display: none;">
                                        <label for="other_department" class="ucua-label">Please specify your department<span class="ucua-required">*</span></label>
                                        <label for="other_department" class="ucua-label-my">Sila Jelaskan Jabatan Anda</label>
                                        <input type="text" name="other_department" id="other_department" class="ucua-input"
                                            placeholder="Enter department name">
                                    </div>
                                    <script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                            const departmentSelect = document.getElementById('department_id');
                                            const otherInputDiv = document.getElementById('other-department-input');

                                            departmentSelect.addEventListener('change', function() {
                                                if (this.value === '0') {
                                                    otherInputDiv.style.display = 'block';
                                                } else {
                                                    otherInputDiv.style.display = 'none';
                                                }
                                            });

                                            // Optional: Show input if "Others" was previously selected
                                            if (departmentSelect.value === '0') {
                                                otherInputDiv.style.display = 'block';
                                            }
                                        });
                                    </script>
                                    @error('department_id')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Section 2 Location Detail --}}
                        <div class="ucua-section" data-step="2">
                            <div class="ucua-section-header">
                                <div class="ucua-section-num">02</div>
                                <div>
                                    <h2 class="ucua-section-title">Where is the location of the Unsafe Condition or Unsafe Act?</h2>
                                    <div class="ucua-section-subtitle">Di manakah lokasi Keadaan Tidak Selamat atau Perbuatan Tidak Selamat yang ingin dilaporkan?</div>
                                </div>
                            </div>

                            {{-- Affected Area --}}
                            <div class="ucua-field">
                                <label for="affected_area" class="ucua-label">Affected Area<span class="ucua-required">*</span></label>
                                <label for="affected_area" class="ucua-label-my">Kawasan Terjejas</label>
                                <input type="text" class="ucua-input" placeholder="Enter here" id="affected_area"
                                    name="affected_area" value="{{ old('affected_area') }}" required>
                                @error('affected_area')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="ucua-grid-2" style="margin-top: 18px;">
                                {{-- Department Responsible --}}
                                <div class="ucua-field">
                                    <label for="dept_res_id" class="ucua-label">Department Responsible<span class="ucua-required">*</span></label>
                                    <label for="dept_res_id" class="ucua-label-my">Jabatan Bertanggungjawab</label>
                                    <select name="dept_res_id" id="dept_res_id" class="ucua-select" required>
                                        <option hidden selected value="">Please choose</option>
                                        @foreach ($departments as $dept)
                                            @if ($dept->subdepartment->count())
                                                <optgroup label="{{ $dept->name }}">
                                                    @foreach ($dept->subdepartment as $sub)
                                                        <option value="sub_{{ $sub->id }}"
                                                            {{ old('dept_res_id') == 'sub_' . $sub->id ? 'selected' : '' }}>
                                                            {{ $sub->name }}
                                                        </option>
                                                    @endforeach
                                                </optgroup>
                                            @else
                                                <option value="dept_{{ $dept->id }}"
                                                    {{ old('dept_res_id') == 'dept_' . $dept->id ? 'selected' : '' }}>
                                                    {{ $dept->name }}
                                                </option>
                                            @endif
                                        @endforeach
                                        <option value="0" {{ old('dept_res_id') == '0' ? 'selected' : '' }}>Others</option>
                                    </select>
                                    <div id="dept_res_other" class="ucua-field" style="margin-top: 10px; display: none;">
                                        <label for="dept_res_other_input" class="ucua-label">Please specify Department Responsible<span class="ucua-required">*</span></label>
                                        <label for="dept_res_other_input" class="ucua-label-my">Sila Jelaskan Jabatan yang Bertanggungjawab</label>
                                        <input type="text" name="dept_res_other" id="dept_res_other_input" class="ucua-input"
                                            placeholder="Enter department name">
                                    </div>
                                    <script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                            const departmentResSelect = document.getElementById('dept_res_id');
                                            const otherInputDiv2 = document.getElementById('dept_res_other');

                                            departmentResSelect.addEventListener('change', function() {
                                                if (this.value === '0') {
                                                    otherInputDiv2.style.display = 'block';
                                                } else {
                                                    otherInputDiv2.style.display = 'none';
                                                }
                                            });

                                            // Optional: Show input if "Others" was previously selected
                                            if (departmentResSelect.value === '0') {
                                                otherInputDiv2.style.display = 'block';
                                            }
                                        });
                                    </script>
                                    @error('dept_res_id')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Plant Involved --}}
                                <div class="ucua-field">
                                    <label for="plant_inv_id" class="ucua-label">Plant Involved<span class="ucua-required">*</span></label>
                                    <label for="plant_inv_id" class="ucua-label-my">Loji Terlibat</label>
                                    <select name="plant_inv_id" id="plant_inv_id" class="ucua-select" required>
                                        <option hidden selected value="">Please choose</option>
                                        @foreach ($plants as $plant)
                                            <option value="{{ $plant->id }}"
                                                {{ old('plant_inv_id') == $plant->id ? 'selected' : '' }}>
                                                {{ $plant->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('plant_inv_id')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Section 3 Unsafe Conditions or Unsafe Act --}}
                        <div class="ucua-section" data-step="3">
                            <div class="ucua-section-header">
                                <div class="ucua-section-num">03</div>
                                <div>
                                    <h2 class="ucua-section-title">Please choose either Unsafe Condition or Unsafe Act<span class="ucua-required">*</span></h2>
                                    <div class="ucua-section-subtitle">Sila pilih Keadaan Tidak Selamat atau Perbuatan Tidak Selamat</div>
                                </div>
                            </div>

                            <div class="ucua-grid-2">
                                <label class="ucua-radio-card">
                                    <input type="radio" class="ucua-radio-input"
                                        onchange="toggleSections('unsafeConditionSection')" value="unsafe_condition"
                                        name="entry_unsafe_condition_act" required
                                        @if (old('entry_unsafe_condition_act')) checked @endif>
                                    <div>
                                        <div class="ucua-radio-text">Unsafe Condition</div>
                                        <div class="ucua-radio-text-my">Keadaan Tidak Selamat</div>
                                    </div>
                                </label>
                                <label class="ucua-radio-card">
                                    <input type="radio" class="ucua-radio-input"
                                        onchange="toggleSections('unsafeActSection')" value="unsafe_act"
                                        name="entry_unsafe_condition_act" required
                                        @if (old('entry_unsafe_condition_act')) checked @endif>
                                    <div>
                                        <div class="ucua-radio-text">Unsafe Act</div>
                                        <div class="ucua-radio-text-my">Perbuatan Tidak Selamat</div>
                                    </div>
                                </label>
                            </div>
                            @error('entry_unsafe_condition_act')
                                <div class="alert alert-danger" style="margin-top: 12px;">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Section 4 Unsafe Conditions --}}
                        <div class="ucua-section" data-step="4" id="unsafeConditionSection" style="display: none;">
                            <div class="ucua-section-header">
                                <div class="ucua-section-num">04</div>
                                <div>
                                    <h2 class="ucua-section-title">What is the Unsafe Condition that I want to report?<span class="ucua-required">*</span></h2>
                                    <div class="ucua-section-subtitle">Apakah Keadaan Tidak Selamat yang ingin saya laporkan?</div>
                                </div>
                            </div>

                            <div class="ucua-grid-2">
                                @forelse ($conditions as $item)
                                    <label class="ucua-radio-row">
                                        <input type="radio" class="ucua-radio-input"
                                            onchange="handleChangeOtherCheckbox('other-uc-checkbox','unsafe_cond_other')"
                                            value="{{ $item->id }}" name="entry_unsafe"
                                            @if (is_array(old('entry_unsafe')) && in_array($item->id, old('entry_unsafe'))) checked @endif>
                                        <div>
                                            <div class="ucua-radio-text-plain">{{ $item->name }}</div>
                                            <div class="ucua-radio-text-my">{{ $item->name_my }}</div>
                                        </div>
                                    </label>
                                @empty
                                    No item
                                @endforelse
                                <label class="ucua-radio-row" style="align-items: center;">
                                    <input type="radio" class="ucua-radio-input other-uc-checkbox"
                                        onchange="handleChangeOtherCheckbox('other-uc-checkbox','unsafe_cond_other')"
                                        value="0" name="entry_unsafe"
                                        @if (is_array(old('unsafe_conditions')) && in_array('0', old('unsafe_conditions'))) checked @endif>
                                    <div style="flex-grow: 1;">
                                        <div class="ucua-radio-text-plain">Others - The Unsafe Condition which is not in the list
                                            <span class="ucua-required" style="font-size: 11.5px; font-style: italic;">Max 50 characters</span>
                                        </div>
                                        <div class="ucua-radio-text-my">Lain-lain - Keadaan Tidak Selamat yang tiada dalam senarai
                                            <i>Maks. 50 aksara</i>
                                        </div>
                                    </div>
                                </label>
                            </div>
                            @error('entry_unsafe')
                                <div class="alert alert-danger" style="margin-top: 12px;">{{ $message }}</div>
                            @enderror
                            <input class="ucua-input" type="text" name="unsafe_cond_other"
                                placeholder="Please specify here if other" value="{{ old('unsafe_cond_other') }}"
                                maxlength="50" style="margin-top: 14px;">
                        </div>

                        {{-- Section 5 Unsafe Acts --}}
                        <div class="ucua-section" data-step="4" id="unsafeActSection" style="display: none;">
                            <div class="ucua-section-header">
                                <div class="ucua-section-num">04</div>
                                <div>
                                    <h2 class="ucua-section-title">What is the Unsafe Acts that I want to report?<span class="ucua-required">*</span></h2>
                                    <div class="ucua-section-subtitle">Apakah Perbuatan Tidak Selamat yang ingin saya laporkan?</div>
                                </div>
                            </div>

                            <div class="ucua-grid-2">
                                @forelse ($acts as $item)
                                    <label class="ucua-radio-row">
                                        <input type="radio" class="ucua-radio-input"
                                            onchange="handleChangeOtherCheckbox('other-ua-checkbox','unsafe_act_other')"
                                            value="{{ $item->id }}" name="entry_unsafe"
                                            @if (is_array(old('entry_unsafe')) && in_array($item->id, old('entry_unsafe'))) checked @endif>
                                        <div>
                                            <div class="ucua-radio-text-plain">{{ $item->name }}</div>
                                            <div class="ucua-radio-text-my">{{ $item->name_my }}</div>
                                        </div>
                                    </label>
                                @empty
                                    No item
                                @endforelse
                                <label class="ucua-radio-row" style="align-items: center;">
                                    <input type="radio" class="ucua-radio-input other-ua-checkbox"
                                        onchange="handleChangeOtherCheckbox('other-ua-checkbox','unsafe_act_other')"
                                        value="0" name="entry_unsafe"
                                        @if (is_array(old('unsafe_acts')) && in_array('0', old('unsafe_acts'))) checked @endif>
                                    <div style="flex-grow: 1;">
                                        <div class="ucua-radio-text-plain">Others - The Unsafe Act which is not in the list
                                            <span class="ucua-required" style="font-size: 11.5px; font-style: italic;">Max 50 characters</span>
                                        </div>
                                        <div class="ucua-radio-text-my">Lain-lain - Keadaan Tidak Selamat yang tiada dalam senarai
                                            <i>Maks. 50 aksara</i>
                                        </div>
                                    </div>
                                </label>
                            </div>
                            @error('entry_unsafe')
                                <div class="alert alert-danger" style="margin-top: 12px;">{{ $message }}</div>
                            @enderror
                            <input class="ucua-input" type="text" name="unsafe_act_other"
                                placeholder="Please specify here if other" value="{{ old('unsafe_act_other') }}"
                                maxlength="50" style="margin-top: 14px;">
                        </div>

                        <script>
                            function toggleSections(sectionToShow) {
                                // Hide both sections initially
                                document.getElementById('unsafeConditionSection').style.display = 'none';
                                document.getElementById('unsafeActSection').style.display = 'none';

                                // Show the selected section
                                if (sectionToShow === 'unsafeConditionSection') {
                                    document.getElementById('unsafeConditionSection').style.display = 'block';
                                } else if (sectionToShow === 'unsafeActSection') {
                                    document.getElementById('unsafeActSection').style.display = 'block';
                                }
                            }

                            document.addEventListener('DOMContentLoaded', function() {
                                const selectedOption = "{{ old('entry_unsafe_condition_act') }}";
                                if (selectedOption === 'unsafe_condition') {
                                    toggleSections('unsafeConditionSection');
                                } else if (selectedOption === 'unsafe_act') {
                                    toggleSections('unsafeActSection');
                                }
                            });

                            hideInput('unsafe_cond_other');
                            hideInput('unsafe_act_other');

                            $(document).ready(function() {
                                handleChangeOtherCheckbox('other-uc-checkbox', 'unsafe_cond_other');
                                handleChangeOtherCheckbox('other-ua-checkbox', 'unsafe_act_other');
                            });

                            function handleChangeOtherCheckbox(classNameCustom, targetInputName) {
                                if ($('input.' + classNameCustom).is(':checked')) {
                                    showInput(targetInputName);
                                } else {
                                    hideInput(targetInputName);
                                }
                            }

                            function hideInput(targetInputName) {
                                $('[name="' + targetInputName + '"]').hide();
                            }

                            function showInput(targetInputName) {
                                $('[name="' + targetInputName + '"]').show();
                            }
                        </script>

                        {{-- Section 5 Description --}}
                        <div class="ucua-section" data-step="5">
                            <div class="ucua-section-header">
                                <div class="ucua-section-num">05</div>
                                <div>
                                    <h2 class="ucua-section-title">My Explanation<span class="ucua-required">*</span></h2>
                                    <div class="ucua-section-subtitle">Penjelasan Saya</div>
                                </div>
                            </div>

                            {{-- Description --}}
                            <div class="ucua-field">
                                <div class="ucua-helper">Please explain the issue in detail<br>
                                    <i>Sila jelaskan isu ini secara terperinci</i>
                                </div>
                                <textarea name="description" class="ucua-textarea" id="description" cols="30" rows="5" required
                                    placeholder="Enter here">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div style="margin-top: 18px;">
                                {{-- Stop Culture --}}
                                <div class="ucua-field">
                                    <label for="stop_cult_id" class="ucua-label">Stop Culture<span class="ucua-required">*</span></label>
                                    <label for="stop_cult_id" class="ucua-label-my">Budaya Berhenti</label>
                                    <select name="stop_cult_id" id="stop_cult_id" class="ucua-select" required>
                                        <option hidden selected value="">Please choose</option>
                                        @foreach ($stop_cults as $stop_cult)
                                            <option value="{{ $stop_cult->id }}"
                                                {{ old('stop_cult_id') == $stop_cult->id ? 'selected' : '' }}>
                                                {{ $stop_cult->name }} - {{ $stop_cult->description }}</option>
                                        @endforeach
                                    </select>
                                    @error('stop_cult_id')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Section 6 Picture Upload --}}
                        <div class="ucua-section" data-step="6">
                            <div class="ucua-section-header">
                                <div class="ucua-section-num">06</div>
                                <div>
                                    <h2 class="ucua-section-title">My Supporting Document<span class="ucua-required">*</span></h2>
                                    <div class="ucua-section-subtitle">Dokumen Sokongan Saya</div>
                                </div>
                            </div>

                            <div class="ucua-helper" style="margin-bottom: 16px;">
                                Only PNG, JPEG &amp; GIF file format supported<br>
                                <i>Hanya format PNG, JPEG &amp; GIF yang disokong</i><br>
                                Total max size of pictures must be less than <b>10MB</b><br>
                                <i>Jumlah saiz maksimum gambar mestilah kurang daripada 10MB</i><br>
                                Please upload 2 types of picture: <b>Before (Must)</b> &amp; <b>Correction (Optional)</b>.<br>
                                <i>Sila kemukakan 2 jenis gambar: Sebelum (Wajib) &amp; Pembetulan (Pilihan).</i>
                            </div>

                            <div class="ucua-grid-2">
                                {{-- Before --}}
                                <div class="ucua-field">
                                    <label for="attachment_before" class="ucua-label">Before (Must)<span class="ucua-required">*</span></label>
                                    <label for="attachment_before" class="ucua-label-my">Sebelum (Wajib)</label>
                                    <div class="ucua-upload-box">
                                        <div class="ucua-upload-head">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#FC471A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 16V4"/><path d="M7 9l5-5 5 5"/><path d="M4 16v3a2 2 0 002 2h12a2 2 0 002-2v-3"/></svg>
                                            <span style="font-size: 12.5px; font-weight: 600; color: #011333;">File(s)</span>
                                        </div>
                                        <input class="ucua-input" type="file" id="attachment_before"
                                            name="attachment_before[]" multiple accept="image/*" data-compress
                                            onchange="handleAttachmentBefore(event.target.files)">
                                        <div class="ucua-file-list display_attachment_before"></div>
                                        <div class="display_size_error_before"></div>
                                    </div>
                                    @error('attachment_before')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                    @error('attachment_before.*')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Correction --}}
                                <div class="ucua-field">
                                    <label for="attachment_correction" class="ucua-label">Correction (Optional)</label>
                                    <label for="attachment_correction" class="ucua-label-my">Pembetulan (Pilihan)</label>
                                    <div class="ucua-upload-box">
                                        <div class="ucua-upload-head">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#8A8578" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 16V4"/><path d="M7 9l5-5 5 5"/><path d="M4 16v3a2 2 0 002 2h12a2 2 0 002-2v-3"/></svg>
                                            <span style="font-size: 12.5px; font-weight: 600; color: #011333;">File(s)</span>
                                        </div>
                                        <input class="ucua-input" type="file" id="attachment_correction"
                                            name="attachment_correction[]" multiple accept="image/*" data-compress
                                            onchange="handleAttachmentCorrection(event.target.files)">
                                        <div class="ucua-file-list display_attachment_correction"></div>
                                        <div class="display_size_error_correction"></div>
                                    </div>
                                    @error('attachment_correction')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                    @error('attachment_correction.*')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <script>
                            function handleAttachmentBefore(files) {
                                $('.display_attachment_before').empty();
                                $('.display_size_error_before').empty();
                                var total_size = 0;
                                [...files].forEach(function(each) {
                                    $('.display_attachment_before').append("<div> - " + each.name + "</div>");
                                    total_size += each.size;
                                });

                                if (total_size >= 10485760) { // if total file size exceed 10 MB
                                    $('.display_size_error_before').append(
                                        "<div style='color:red'><b>Total File Size Exceeded Max (10 MB)</b></div>");
                                }

                            }

                            function handleAttachmentCorrection(files) {
                                $('.display_attachment_correction').empty();
                                $('.display_size_error_correction').empty();
                                var total_size = 0;
                                [...files].forEach(function(each) {
                                    $('.display_attachment_correction').append("<div> - " + each.name + "</div>");
                                    total_size += each.size;
                                });

                                if (total_size >= 10485760) { // if total file size exceed 10 MB
                                    $('.display_size_error_correction').append(
                                        "<div style='color:red'><b>Total File Size Exceeded Max (10 MB)</b></div>");
                                }

                            }
                        </script>

                        {{-- Section 7 Action Taken --}}
                        <div class="ucua-section" data-step="7">
                            <div class="ucua-section-header">
                                <div class="ucua-section-num">07</div>
                                <div>
                                    <h2 class="ucua-section-title">My Action<span class="ucua-required">*</span></h2>
                                    <div class="ucua-section-subtitle">Tindakan Saya</div>
                                </div>
                            </div>

                            <div style="background: #F3F4F6; border-radius: 4px; padding: 14px 16px; margin-bottom: 18px;">
                                <div style="font-size: 15px; color: #1F2933;">I have implemented
                                    <a type="button" data-toggle="modal" data-target="#exampleModal">
                                        <u class="font-bold">Behaviour-Based Safety (BBS)&nbsp;<i
                                                class="bi bi-exclamation-circle-fill"></i>
                                        </u>
                                    </a> methodology and take immediate action to close the observation.
                                </div>
                                <div style="font-size: 13px; color: #6B7280; margin-top: 4px;">Saya telah melaksanakan metodologi
                                    <a type="button" data-toggle="modal" data-target="#exampleModal">
                                        <u class="font-bold">Keselamatan Berasaskan Tingkah Laku
                                            (BBS)&nbsp;<i class="bi bi-exclamation-circle-fill"></i></u></a>
                                    dan mengambil tindakan segera untuk mencegah kemalangan dari
                                    berlaku.
                                </div>
                            </div>

                            <div class="ucua-grid-2">
                                <label class="ucua-radio-card">
                                    <input type="radio" class="ucua-radio-input" value="1" name="bbs_action"
                                        required {{ old('bbs_action') == 1 ? 'checked' : '' }}>
                                    <div>
                                        <div class="ucua-radio-text">Yes, I have implemented the BBS methodology
                                            &nbsp;<a type="button" data-toggle="modal"
                                                data-target="#exampleModal" data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="What is Big Brother, Big Sister?">
                                                <i class="bi bi-exclamation-circle-fill"></i></a>
                                        </div>
                                        <div class="ucua-radio-text-my">Ya, saya mengaplikasi metodologi BBS</div>
                                    </div>
                                </label>
                                <label class="ucua-radio-card">
                                    <input type="radio" class="ucua-radio-input" value="0" name="bbs_action"
                                        required {{ old('bbs_action') == 0 ? 'checked' : '' }}>
                                    <div>
                                        <div class="ucua-radio-text">No BBS action has been taken</div>
                                        <div class="ucua-radio-text-my">Tiada tindakan BBS dijalankan.</div>
                                    </div>
                                </label>
                            </div>
                            @error('bbs_action')
                                <div class="alert alert-danger" style="margin-top: 12px;">{{ $message }}</div>
                            @enderror

                            <div id="bbs_methodology_section" class="ucua-field" style="margin-top: 18px; display: none;">
                                <label class="ucua-label">Please select which 6C methodology you apply? You can choose more than one
                                    <span class="ucua-required bbs_methodology_required">*</span></label>
                                <label class="ucua-label-my">Metodologi 6C (pilih semua yang berkaitan)</label>
                                <div class="ucua-checkbox-grid" style="margin-top: 10px;">
                                    @foreach (['Capture', 'Care', 'Connect', 'Correct', 'Conversation', 'Conclude'] as $methodology)
                                        <label class="ucua-checkbox-chip">
                                            <input type="checkbox" class="ucua-checkbox-input bbs_methodology_checkbox"
                                                name="bbs_methodology[]" value="{{ $methodology }}"
                                                {{ is_array(old('bbs_methodology')) && in_array($methodology, old('bbs_methodology')) ? 'checked' : '' }}>
                                            <span style="font-size: 12.5px;">{{ $methodology }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                @error('bbs_methodology')
                                    <div class="alert alert-danger" style="margin-top: 10px;">{{ $message }}</div>
                                @enderror
                            </div>
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    const bbsRadios = document.getElementsByName('bbs_action');
                                    const bbsMethodologySection = document.getElementById('bbs_methodology_section');
                                    const bbsMethodologyCheckboxes = document.querySelectorAll('.bbs_methodology_checkbox');

                                    function toggleBbsMethodology() {
                                        const checked = document.querySelector('input[name="bbs_action"]:checked');
                                        if (checked && checked.value === '1') {
                                            bbsMethodologySection.style.display = 'block';
                                        } else {
                                            bbsMethodologySection.style.display = 'none';
                                            bbsMethodologyCheckboxes.forEach(function(checkbox) {
                                                checkbox.checked = false;
                                            });
                                        }
                                    }

                                    bbsRadios.forEach(function(radio) {
                                        radio.addEventListener('change', toggleBbsMethodology);
                                    });

                                    toggleBbsMethodology();
                                });
                            </script>

                            {{-- Action Taken --}}
                            <div class="ucua-field" style="margin-top: 18px;">
                                <div class="ucua-helper">Please state if there is any action taken immediately. If no action taken, please state none</div>
                                <textarea name="action_taken" class="ucua-textarea" id="action_taken" cols="30" rows="5"
                                    placeholder="Enter here">{{ old('action_taken') }}</textarea>
                                @error('action_taken')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Submit --}}
                        <div style="text-align: center; padding-top: 24px;">
                            {{-- Cloudflare Turnstile: adds a hidden cf-turnstile-response field to the form --}}
                            <div class="cf-turnstile" data-sitekey="{{ config('services.turnstile.sitekey') }}"
                                style="display: flex; justify-content: center; margin-bottom: 16px;"></div>
                            @error('cf-turnstile-response')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror

                            <button type="submit" class="ucua-btn-primary">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#FFFFFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 2L11 13"/><path d="M22 2l-7 20-4-9-9-4 20-7z"/></svg>
                                Submit my observation now!
                            </button>
                            <div style="font-size: 13px; color: #6B7280; margin-top: 8px;">Hantar pemerhatian saya sekarang!</div>

                            {{-- <button type="submit" class="btn btn-primary btn-block g-recaptcha"
                                data-sitekey="{{ config('services.googleRecaptcha.sitekey') }}" data-callback="onSubmit"
                                data-action="submit" id="submitButton" onclick="disableSubmitButton()">
                                <span id="buttonText">
                                    <strong>Submit my observation now! <br><small><i>Hantar pemerhatian saya
                                                sekarang!</i></small></strong>
                                </span>
                                <span id="loadingSpinner" style="display: none;">
                                    <i class="fa fa-spinner fa-spin"></i>
                                    <strong>Submitting... <br><small><i>Menghantar...</i></small></strong>
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
                                    document.getElementById('newTicketForm').submit();
                                }

                                // Fallback if reCAPTCHA fails
                                document.getElementById('newTicketForm').addEventListener('submit', function() {
                                    disableSubmitButton();
                                });
                            </script> --}}
                        </div>

                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- BBS Modal --}}
    <div class="modal fade" id="exampleModal" tabindex="-1"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">What is BBS?<br>
                        <small><i>Apa itu BBS?</i></small>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5 class="fw-bold" style="color: #011333">Click <a
                            href="{{ config('app.youtube_link') }}" target="_blank"
                            style="color: rgb(252, 71, 26)">HERE&nbsp;<i
                                class="bi bi-youtube"></i></a> for more info.</h5>
                    <h6 class="fw-bold mb-3" style="color: #011333">Klik <a
                            href="{{ config('app.youtube_link') }}" target="_blank"
                            style="color: rgb(252, 71, 26)">SINI&nbsp;<i
                                class="bi bi-youtube"></i></a> untuk maklumat lanjut.</h6>
                    <ul>
                        <li>C - Capture (Mengenal Pasti)</li>
                        <li>C - Care (Ambil Berat)</li>
                        <li>C - Connect (Berhubung)</li>
                        <li>C - Correct (Memperbetulkan)</li>
                        <li>C - Conversation (Berkomunikasi)</li>
                        <li>C - Conclude (Membuat Kesimpulan)</li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
