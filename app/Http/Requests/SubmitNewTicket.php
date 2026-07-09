<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubmitNewTicket extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'regex:/^[a-zA-Z-&.,\/\s]+$/m', 'max:50'],
            'email' => 'required|string|email:rfc',
            'phone_number' => ['required', 'string', 'max:20', 'regex:/^[0-9-+#*]+$/m'],
            // 'staff_id' => ['required', 'string', 'max:20', 'regex:/^[0-9-+#*]+$/m'],
            'staff_id' => ['required', 'string', 'max:20', 'regex:/^[A-Za-z0-9]+$/'],
            'site_id' => 'required',
            'department_id' => 'required',
            'other_department' => ['nullable', 'string', 'regex:/^[a-zA-Z0-9-&.,\(\)\/\s]+$/m', 'max:200'],
            'affected_area' => ['required', 'string', 'regex:/^[a-zA-Z0-9-&.,\(\)\/\s]+$/m', 'max:500'],
            'dept_res_id' => 'required',
            'dept_res_other' => ['nullable', 'string', 'regex:/^[a-zA-Z0-9-&.,\(\)\/\s]+$/m', 'max:200'],
            'plant_inv_id' => 'required',
            'gm_res_id' => 'required',
            'entry_unsafe_condition_act' => 'required',
            'entry_unsafe' => ['nullable'],
            'unsafe_cond_other' => ['nullable', 'string', 'regex:/^[a-zA-Z0-9-&.,\(\)\/\s]+$/m', 'max:50'],
            'unsafe_act_other' => ['nullable', 'string', 'regex:/^[a-zA-Z0-9-&.,\(\)\/\s]+$/m', 'max:50'],
            'description' => 'required|max:500',
            'stop_cult_id' => 'required',
            'zero_harm_id' => 'required',
            'rank_id' => 'required',
            'attachment_before' => 'required|array|max:5',
            'attachment_before.*' => 'image|max:50000',
            'attachment_correction' => 'nullable|array|max:5',
            'attachment_correction.*' => 'image|max:50000',
            'action_taken' => 'required|max:500',
            'bbs_action' => 'required',
        ];
    }
}
