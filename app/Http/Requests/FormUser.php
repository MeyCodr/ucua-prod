<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FormUser extends FormRequest
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
            'email' => ['sometimes', 'required', 'string', 'unique:App\Models\User,email', 'email:rfc'],
            'phone_number' => ['required', 'string', 'max:20', 'regex:/^[0-9-+#*]+$/m'],
            'department_id' => ['required', 'exists:departments,id'],
            'designation' => ['required', 'string', 'regex:/^[a-zA-Z0-9-&.,\/\s]+$/m', 'max:50'],
            'role_id' => ['required'],
            'password' => ['sometimes', 'required', 'confirmed', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d!$%@#£€*?&]{8,}$/'], //Minimum eight characters, at least one uppercase letter, one lowercase letter and one number
            'password_confirmation' => ['sometimes', 'required', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d!$%@#£€*?&]{8,}$/'], //Minimum eight characters, at least one uppercase letter, one lowercase letter and one number
            'is_enabled' => ['sometimes', 'required', 'numeric'],
            'is_locked' => ['sometimes', 'required', 'numeric'],
        ];
    }
}
