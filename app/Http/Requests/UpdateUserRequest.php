<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'first_name' => 'nullable|string|min:3|max:255',
            'last_name' => 'nullable|string|min:3|max:255',
            'last_name' => 'nullable|string|min:3|max:255',
            // 'username' => 'nullable|string|min:5|max:255',
            'password' => 'nullable|string|min:8|max:30',
            'card_number' => 'nullable|string|digits:16',
            'phone_number' => 'nullable|string|digits:11'
        ];
    }
}
