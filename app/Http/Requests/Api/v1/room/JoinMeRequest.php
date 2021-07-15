<?php

namespace App\Http\Requests\Api\v1\room;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class JoinMeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_id' => ['required', 'exists:App\Models\User,id'],
            'room_id' => ['required', 'exists:App\Models\Room,id'],
        ];
    }
}
