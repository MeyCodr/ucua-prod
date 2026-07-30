<x-guest-layout>
    <div class="flex justify-between w-full px-8 pt-3">
        <img src="{{ asset('/img/phn-logo.png') }}" alt="PHN Logo" style="width: auto;">
        <img src="{{ asset('/img/zero_harm.png') }}" alt="Zero Harm Logo" style="width: 150px;height: auto">
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- Validation Errors -->
    <x-auth-validation-errors class="mb-4" :errors="$errors" />

    @component('Style')
    @endcomponent

    <div class="my-2 pt-5 text-center">
        <div class="font-bold">
            You are a step away to make the workplace a safer place! <br>
            <small><i>Anda hanya tinggal selangkah sahaja lagi untuk memastikan tempat kerja yang lebih
                    selamat!</i></small>
        </div>
        <div class="font-bold mt-3">
            Report your observation here! <br>
            <small><i>Laporkan pemerhatian anda di sini!</i></small>
        </div>
    </div>

    <script src="https://www.google.com/recaptcha/api.js"></script>

    <script>
        function onSubmit(token) {
            document.getElementById("newTicketForm").submit();
        }
    </script>

    <form action="{{ route('SubmitNewTicket') }}" method="post" enctype="multipart/form-data" id="newTicketForm">
        @csrf
        <div class="col-sm-12 col-md-9 mx-auto">
            <div class="flex justify-end mb-2">
                <a href="{{ url('/') }}" class="btn btn-primary">Home</a>
            </div>
            {{-- Section 1 Contact Detail --}}
            <div class="mb-2">
                @component('Ticket.SectionTitle')
                    @slot('title')
                        My Details <br>
                        <small><i>Butiran Saya</i></small>
                    @endslot
                    @slot('section')
                        1
                    @endslot
                @endcomponent
                {{-- Section 1 Contact Detail --}}
                <div class="collapse show shadow-md rounded-b-lg py-2 px-3 block bg-white" id="section1">
                    {{-- Full name --}}
                    <div class="form-group">
                        <label for="name" class="font-semibold">My Name <span class="text-red-600">*</span><br>
                            <small><i>Nama Saya</i></small></label>
                        <input type="text" class="form-control" placeholder="Enter here" id="name"
                            name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Email Address --}}
                    <div class="form-group">
                        <label for="email" class="font-semibold">My Email Address <span
                                class="text-red-600">*</span><br>
                            <small><i>Alamat Email Saya</i></small></label>
                        <input type="email" class="form-control" placeholder="Enter here" id="email"
                            name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Staff ID --}}
                    <div class="form-group">
                        <label for="staff_id" class="font-semibold">My Staff ID <span class="text-red-600">*</span><br>
                            <small><i>ID Staf Saya</i></small></label>
                        <input type="text" class="form-control" placeholder="e.g. 0123456789" id="staff_id"
                            name="staff_id" value="{{ old('staff_id') }}" required>
                        @error('staff_id')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Phone No. --}}
                    <div class="form-group">
                        <label for="phone_number" class="font-semibold">My Phone No. <span
                                class="text-red-600">*</span><br>
                            <small><i>No. Telefon Saya</i></small></label>
                        <input type="text" class="form-control" placeholder="Enter here" id="phone_number"
                            name="phone_number" value="{{ old('phone_number') }}" required>
                        @error('phone_number')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Site Name --}}
                    <div class="form-group">
                        <label for="site_id" class="font-semibold">My Site <span class="text-red-600">*</span><br>
                            <small><i>Tapak Saya</i></small></label>
                        <select name="site_id" id="site_id" class="form-control" required>
                            <option hidden selected value="">Please choose</option>
                            @foreach ($sites as $site)
                                <option value="{{ $site->id }}"
                                    {{ old('site_id') == $site->id ? 'selected' : '' }}>
                                    {{ $site->name }}</option>
                            @endforeach
                        </select>
                        @error('site_id')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Department Name --}}
                    <div class="form-group">
                        <label for="department_id" class="font-semibold">My Department
                            <span class="text-red-600">*</span> <br>
                            <small><i>Jabatan Saya</i></small></label>
                        <select name="department_id" id="department_id" class="form-control" required>
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
                        <div id="other-department-input" class="mt-2" style="display: none;">
                            <label for="other_department" class="font-semibold">Please specify your department
                                <span class="text-red-600">*</span><br>
                                <small><i>Sila Jelaskan Jabatan Anda</i></small></label>
                            <input type="text" name="other_department" id="other_department" class="form-control"
                                placeholder="Enter department name">
                        </div>
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

            {{-- Section 2 Location Detail --}}
            <div class="mb-2">
                @component('Ticket.SectionTitle')
                    @slot('title')
                        Where is the location of the Unsafe Condition or Unsafe Act <br>
                        <small><i>Di manakah lokasi Keadaan Tidak Selamat atau Perbuatan Tidak Selamat yang ingin
                                dilaporkan?</i></small>
                    @endslot
                    @slot('section')
                        2
                    @endslot
                @endcomponent

                <div class="collapse show shadow-md rounded-b-lg py-2 px-3 block bg-white" id="section2">

                    {{-- Affected Area --}}
                    <div class="form-group mt-2">
                        <label for="affected_area" class="font-semibold">Affected Area <span
                                class="text-red-600">*</span><br>
                            <small><i>Kawasan Terjejas</i></small></label>
                        <input type="text" class="form-control" placeholder="Enter here" id="affected_area"
                            name="affected_area" value="{{ old('affected_area') }}" required>
                        @error('affected_area')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Department Responsible --}}
                    <div class="form-group mt-2">
                        <label for="dept_res_id" class="font-semibold">Department Responsible
                            <span class="text-red-600">*</span><br>
                            <small><i>Jabatan Bertanggungjawab</i></small></label>
                        <select name="dept_res_id" id="dept_res_id" class="form-control" required>
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
                        <div id="dept_res_other" class="mt-2" style="display: none;">
                            <label for="dept_res_other" class="font-semibold">Please specify Department Responsible
                                <span class="text-red-600">*</span><br>
                                <small><i>Sila Jelaskan Jabatan yang Bertanggungjawab</i></small></label>
                            <input type="text" name="dept_res_other" id="dept_res_other" class="form-control"
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
                    <div class="form-group mt-2">
                        <label for="plant_inv_id" class="font-semibold">Plant Involved<br>
                            <small><i>Loji Terlibat</i></small></label>
                        <select name="plant_inv_id" id="plant_inv_id" class="form-control">
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

                    {{-- GM Responsible --}}
                    <div class="form-group mt-2">
                        <label for="gm_res_id" class="font-semibold">GM Responsible
                            <span class="text-red-600">*</span><br>
                            <small><i>GM Bertanggungjawab</i></small></label>
                        <select name="gm_res_id" id="gm_res_id" class="form-control" required>
                            <option hidden selected value="">Please choose</option>
                            @foreach ($hods as $hod)
                                @if ($hod->head_div)
                                    <optgroup label="{{ $hod->name }}">
                                        <option value="{{ $hod->head_div->id }}"
                                            {{ old('gm_res_id') == $hod->head_div->id ? 'selected' : '' }}>
                                            {{ $hod->head_div->name }} - {{ $hod->head_div->email }}
                                        </option>
                                    </optgroup>
                                @endif
                            @endforeach
                        </select>
                        @error('gm_res_id')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Section 3 Unsafe Conditions or Unsafe Act --}}
            <div class="mb-2">
                @component('Ticket.SectionTitle')
                    @slot('title')
                        Please choose either Unsafe Condition or Unsafe Act. <span class="text-red-600">*</span><br>
                        <small><i>Sila pilih Keadaan Tidak Selamat atau Perbuatan Tidak Selamat.</i></small>
                    @endslot
                    @slot('section')
                        3
                    @endslot
                @endcomponent

                <div class="collapse show shadow-md rounded-b-lg py-2 px-3 block bg-white" id="section3">
                    <div class="form-group">
                        <div class="form-check mb-2">
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input"
                                    onchange="toggleSections('unsafeConditionSection')" value="unsafe_condition"
                                    name="entry_unsafe_condition_act" required
                                    @if (old('entry_unsafe_condition_act')) checked @endif>&nbsp;Unsafe Condition
                                <br><small><i>Keadaan Tidak Selamat</i></small>
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input"
                                    onchange="toggleSections('unsafeActSection')" value="unsafe_act"
                                    name="entry_unsafe_condition_act" required
                                    @if (old('entry_unsafe_condition_act')) checked @endif>&nbsp;Unsafe Act
                                <br><small><i>Perbuatan Tidak Selamat</i></small>
                            </label>
                        </div>
                        @error('entry_unsafe_condition_act')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Section 4 Unsafe Conditions --}}
            <div class="mb-2" id="unsafeConditionSection" style="display: none;">
                @component('Ticket.SectionTitle')
                    @slot('title')
                        What is the Unsafe Condition that I want to report? <span class="text-red-600">*</span><br>
                        <small><i>Apakah Keadaan Tidak Selamat yang ingin saya laporkan?</i></small>
                    @endslot
                    @slot('section')
                        4
                    @endslot
                @endcomponent

                <div class="collapse show shadow-md rounded-b-lg py-2 px-3 block bg-white" id="section4">
                    <div class="form-group">
                        @forelse ($conditions as $item)
                            <div class="form-check mb-2">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input"
                                        onchange="handleChangeOtherCheckbox('other-uc-checkbox','unsafe_cond_other')"
                                        value="{{ $item->id }}" name="entry_unsafe"
                                        @if (is_array(old('entry_unsafe')) && in_array($item->id, old('entry_unsafe'))) checked @endif>&nbsp;{{ $item->name }}
                                    <br><small><i>{{ $item->name_my }}</i></small>
                                </label>
                            </div>
                        @empty
                            No item
                        @endforelse
                        <div class="form-check">
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input other-uc-checkbox"
                                    onchange="handleChangeOtherCheckbox('other-uc-checkbox','unsafe_cond_other')"
                                    value="0" name="entry_unsafe"
                                    @if (is_array(old('unsafe_conditions')) && in_array('0', old('unsafe_conditions'))) checked @endif>&nbsp;Others - The Unsafe
                                Condition which is not in the list &nbsp;&nbsp;
                                <small class="text-danger"><i>Max 50 characters</i></small><br>
                                <small><i>Lain-lain - Keadaan Tidak Selamat yang tiada dalam
                                        senarai</i></small>&nbsp;&nbsp;
                                <small class="text-danger"><i>Maks. 50 aksara</i></small>
                            </label>
                        </div>
                        @error('entry_unsafe')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                        <input class="form-control mt-2" type="text" name="unsafe_cond_other"
                            placeholder="Please specify here if other" value="{{ old('unsafe_cond_other') }}"
                            maxlength="50">
                    </div>
                </div>
            </div>

            {{-- Section 5 Unsafe Acts --}}
            <div class="mb-2" id="unsafeActSection" style="display: none;">
                @component('Ticket.SectionTitle')
                    @slot('title')
                        What is the Unsafe Acts that I want to report? <span class="text-red-600">*</span><br>
                        <small><i>Apakah Perbuatan Tidak Selamat yang ingin saya laporkan?</i></small>
                    @endslot
                    @slot('section')
                        5
                    @endslot
                @endcomponent

                <div class="collapse show shadow-md rounded-b-lg py-2 px-3 block bg-white" id="section5">
                    <div class="form-group">
                        @forelse ($acts as $item)
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input"
                                        onchange="handleChangeOtherCheckbox('other-ua-checkbox','unsafe_act_other')"
                                        value="{{ $item->id }}" name="entry_unsafe"
                                        @if (is_array(old('entry_unsafe')) && in_array($item->id, old('entry_unsafe'))) checked @endif>&nbsp;{{ $item->name }}
                                    <br>
                                    <small><i>{{ $item->name_my }}</i></small>
                                </label>
                            </div>
                        @empty
                            No item
                        @endforelse
                        <div class="form-check">
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input other-ua-checkbox"
                                    onchange="handleChangeOtherCheckbox('other-ua-checkbox','unsafe_act_other')"
                                    value="0" name="entry_unsafe"
                                    @if (is_array(old('unsafe_acts')) && in_array('0', old('unsafe_acts'))) checked @endif>&nbsp;Others - The Unsafe
                                Act which is not in the list &nbsp;&nbsp;
                                <small class="text-danger"><i>Max 50 characters</i></small><br>
                                <small><i>Lain-lain - Keadaan Tidak Selamat yang tiada dalam
                                        senarai</i></small>&nbsp;&nbsp;
                                <small class="text-danger"><i>Maks. 50 aksara</i></small>
                            </label>
                        </div>

                        @error('entry_unsafe')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                        <input class="form-control mt-2" type="text" name="unsafe_act_other"
                            placeholder="Please specify here if other" value="{{ old('unsafe_act_other') }}"
                            maxlength="50">
                    </div>
                </div>
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
            <div class="mb-2">
                @component('Ticket.SectionTitle')
                    @slot('title')
                        My explanation <span class="text-red-600">*</span><br>
                        <small><i>Penjelasan saya</i></small>
                    @endslot
                    @slot('section')
                        6
                    @endslot
                @endcomponent

                <div class="collapse show shadow-md rounded-b-lg py-2 px-3 block bg-white" id="section6">
                    {{-- Description --}}
                    <div class="form-group">
                        <div class="text-gray-500 italic text-sm">Please explain the issue in detail<br>
                            <small>Sila jelaskan isu ini secara terperinci</small>
                        </div>
                        <textarea name="description" class="form-control" id="description" cols="30" rows="5" required
                            placeholder="Enter here">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Stop Culture --}}
                    <div class="form-group mt-2">
                        <label for="stop_cult_id" class="font-semibold">Stop Culture
                            <span class="text-red-600">*</span><br>
                            <small><i>Budaya Berhenti</i></small></label>
                        <select name="stop_cult_id" id="stop_cult_id" class="form-control" required>
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

                    {{-- Zero Harm Rules --}}
                    <div class="form-group mt-2">
                        <label for="zero_harm_id" class="font-semibold">Zero Harm Rules
                            <span class="text-red-600">*</span><br>
                            <small><i>Peraturan Zero Harm</i></small></label>
                        <select name="zero_harm_id" id="zero_harm_id" class="form-control" required>
                            <option hidden selected value="">Please choose</option>
                            @foreach ($zero_harms as $zero_harm)
                                <option value="{{ $zero_harm->id }}"
                                    {{ old('zero_harm_id') == $zero_harm->id ? 'selected' : '' }}>
                                    {{ $zero_harm->name }} - {{ $zero_harm->description }}</option>
                            @endforeach
                        </select>
                        @error('zero_harm_id')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Rank --}}
                    <div class="form-group mt-2">
                        <label for="rank_id" class="font-semibold">Rank
                            <span class="text-red-600">*</span><br>
                            <small><i>Pangkat</i></small></label>
                        <select name="rank_id" id="rank_id" class="form-control" required>
                            <option hidden selected value="">Please choose</option>
                            @foreach ($ranks as $rank)
                                <option value="{{ $rank->id }}"
                                    {{ old('rank_id') == $rank->id ? 'selected' : '' }}>
                                    {{ $rank->name }} - {{ $rank->description }}</option>
                            @endforeach
                        </select>
                        @error('rank_id')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Section 6 Picture Upload --}}
            <div class="mb-2">
                @component('Ticket.SectionTitle')
                    @slot('title')
                        My supporting document <span class="text-red-600">*</span><br>
                        <small><i>Dokumen sokongan saya</i></small>
                    @endslot
                    @slot('section')
                        5
                    @endslot
                @endcomponent

                <div class="collapse show shadow-md rounded-b-lg py-2 px-3 block bg-white" id="section5">
                    {{-- Upload Media --}}
                    <div class="form-group">
                        <div class="mb-2">
                            <div class="text-gray-500 italic text-sm">Only PNG, JPEG & GIF file format
                                supported
                                &nbsp;&nbsp;<small><i>Hanya format PNG, JPEG & GIF yang disokong</i></small>
                            </div>
                            <div class="text-gray-500 italic text-sm">Total max size of pictures must be less
                                than
                                10MB &nbsp;&nbsp;<small><i>Jumlah saiz maksimum gambar mestilah kurang daripada
                                        10MB</i></small>
                            </div>
                            <div class="text-gray-500 italic text-sm">Please upload 2 types of picture:
                                Before (Must) & Correction (Optional).
                                &nbsp;&nbsp;<small><i>Sila kemukakan 2 jenis
                                        gambar: Sebelum (Wajib) & Pembetulan (Pilihan).</i></small>
                            </div>
                        </div>
                        <div class="form-group mt-2">
                            <label for="attachment_before" class="font-semibold">Before (Must)
                                <span class="text-red-600">*</span><br>
                                <small><i>Sebelum (Wajib)</i></small></label>
                            <input class="form-control" type="file" id="attachment_before"
                                name="attachment_before[]" multiple
                                onchange="handleAttachmentBefore(event.target.files)">
                            <div>File(s)</div>
                            <div class="display_attachment_before"></div>
                            <div class="display_size_error_before"></div>
                            @error('attachment_before')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            @error('attachment_before.*')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mt-2">
                            <label for="attachment_correction" class="font-semibold">Correction (Optional)
                                <br>
                                <small><i>Pembetulan (Pilihan)</i></small></label>
                            <input class="form-control" type="file" id="attachment_correction"
                                name="attachment_correction[]" multiple
                                onchange="handleAttachmentCorrection(event.target.files)">
                            <div>File(s)</div>
                            <div class="display_attachment_correction"></div>
                            <div class="display_size_error_correction"></div>
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
            </div>

            {{-- Section 7 Action Taken --}}
            <div class="mb-2">
                @component('Ticket.SectionTitle')
                    @slot('title')
                        My action <span class="text-red-600">*</span><br>
                        <small><i>Tindakan saya</i></small>
                    @endslot
                    @slot('section')
                        7
                    @endslot
                @endcomponent

                {{-- Section 7 Action Taken --}}
                <div class="collapse show shadow-md rounded-b-lg py-2 px-3 block bg-white" id="section7">
                    {{-- Action Taken --}}
                    <div class="form-group">
                        <div class="my-3">
                            <div class="text-gray-900 mb-2">I have implemented
                                <a type="button" data-toggle="modal" data-target="#exampleModal">
                                    <u class="font-bold">Behaviour-Based Safety (BBS)&nbsp;<i
                                            class="bi bi-exclamation-circle-fill"></i>
                                    </u>
                                </a> methodology and take immediate action to close the observation.<br>
                                <small><i>Saya telah melaksanakan metodologi
                                        <a type="button" data-toggle="modal" data-target="#exampleModal">
                                            <u class="font-bold">Keselamatan Berasaskan Tingkah Laku
                                                (BBS)&nbsp;<i class="bi bi-exclamation-circle-fill"></i></u></a>
                                        dan mengambil tindakan segera untuk mencegah kemalangan dari
                                        berlaku.</i></small>
                            </div>
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
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input" value="1" name="bbs_action"
                                        required {{ old('bbs_action') == 1 ? 'checked' : '' }}>&nbsp;Yes, I have
                                    implemented the
                                    BBS methodology &nbsp;&nbsp;<a type="button" data-toggle="modal"
                                        data-target="#exampleModal" data-bs-toggle="tooltip" data-bs-placement="top"
                                        title="What is Big Brother, Big Sister?">
                                        <i class="bi bi-exclamation-circle-fill"></i></a><br>
                                    <small><i>Ya , saya mengaplikasi metodologi BBS
                                        </i></small>
                                </label>
                            </div>
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input" value="0" name="bbs_action"
                                        required {{ old('bbs_action') == 0 ? 'checked' : '' }}>&nbsp;No
                                    BBS action
                                    has been
                                    taken<br>
                                    <small><i>Tiada tindakan BBS dijalankan.</i></small>
                                </label>
                            </div>
                            @error('bbs_action')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            <div id="bbs_methodology_section" class="form-group mt-2" style="display: none;">
                                <label class="font-semibold">Please select which 6C methodology you apply? You can choose more than one
                                    <span class="text-red-600 bbs_methodology_required">*</span><br>
                                    <small><i>Metodologi 6C (pilih semua yang berkaitan)</i></small></label>
                                @foreach (['Capture', 'Care', 'Connect', 'Correct', 'Conversation', 'Conclude'] as $methodology)
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input type="checkbox" class="form-check-input bbs_methodology_checkbox"
                                                name="bbs_methodology[]" value="{{ $methodology }}"
                                                {{ is_array(old('bbs_methodology')) && in_array($methodology, old('bbs_methodology')) ? 'checked' : '' }}>&nbsp;{{ $methodology }}
                                        </label>
                                    </div>
                                @endforeach
                                @error('bbs_methodology')
                                    <div class="alert alert-danger">{{ $message }}</div>
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
                        </div>
                        <div class="text-gray-500 italic text-sm mt-2 mb-0">Please state if there is any action
                            taken
                            immediately. If no action taken, please state none</div>
                        <textarea name="action_taken" class="form-control" id="action_taken" cols="30" rows="5"
                            placeholder="Enter here">{{ old('action_taken') }}</textarea>
                        @error('action_taken')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="text-center mb-4">
                <button type="submit" class="btn btn-primary btn-block">
                    <strong>Submit my observation now! <br><small><i>Hantar pemerhatian saya
                                sekarang!</i></small></strong>
                </button>

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
    </form>
</x-guest-layout>
