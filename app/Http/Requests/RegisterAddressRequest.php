<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterAddressRequest extends FormRequest
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
            'province' => 'required|string|min:2|max:100',
            'city' => 'required|string|min:2|max:100',
            'address' => 'required|string|max:255',
            'postal_code' => 'nullable|unique:addresses|string|size:10',
            'plaque' => 'nullable|integer|max_digits:4',
        ];
    }
}
