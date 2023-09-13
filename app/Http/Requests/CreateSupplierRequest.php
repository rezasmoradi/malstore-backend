<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateSupplierRequest extends FormRequest
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
            'first_name' => 'nullable|string|max:100',
            'last_name' => 'nullable|string|max:255',
            'company' => 'required|string|max:255|unique:suppliers',
            'business_id' => 'required|exists:businesses,id',
            'province' => 'required|string|min:2|max:100',
            'city' => 'required|string|min:2|max:100',
            'address' => 'nullable|string|max:255',
            'phones' => 'required|array',
            'phones.*' => 'required|numeric|max_digits:14'
        ];
    }
}
