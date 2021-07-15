<?php

namespace App\Http\Requests\Api\v1\authentication;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'name' => ['required', 'string'],
            'email' => ['required', 'email'],
            'password' => ['required', Password::min(8)->symbols(true)->mixedCase(true)],
            'confirmation_password' => ['required', Password::min(8)->symbols(true)->mixedCase(true)],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The user name is required',
            'email.required' => 'The user email is required',
            'password.required' => 'The password is required, Please write a valid password.',
            'confirmation_password.required' => 'The validation password is required, Please write a valid password.'
        ];
    }

    /**
     * validate equal emails
     *
     * @param  mixed $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->password != $this->confirmation_password) {
                $validator->errors()->add('invalid_password', 'Both passwords must be equal.');
            }

            $user = User::where('email', $this->email)->exists();
            if ($user) {
                $validator->errors()->add('user_exists', 'The user has an account registered');
            }
        });
    }
}
