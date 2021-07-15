<?php

namespace App\Http\Requests\Api\v1\authentication;

use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class AuthRequest extends FormRequest
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
            'email' => ['required', 'email'],
            'password' => ['required', Password::min(8)->symbols(true)->mixedCase(true)],
        ];
    }

    /**
     * validation messages
     *
     * @return array
     */
    public function messages()
    {
        return [
            'email.required' => 'The email is required, Please write a valid email.',
            'password.required' => 'The password is required, Please write a valid password.',
        ];
    }
}
