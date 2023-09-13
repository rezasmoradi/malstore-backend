<?php

namespace App\Http\Requests;

use App\Rules\PhoneNumber;
use Illuminate\Foundation\Http\FormRequest;

class UpdateShopperRequest extends FormRequest
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
            'first_name' => 'nullable|string|min:3|max:100',
            'last_name' => 'nullable|string|min:3|max:255',
            'shop_name' => 'nullable|string|max:255',
            'phones' => 'nullable|array',
            'phones.*' => ['numeric', 'max_digits:14', new PhoneNumber()]
        ];
    }
}
