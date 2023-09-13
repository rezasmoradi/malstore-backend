<?php

namespace App\Http\Requests;

use App\Models\Address;
use App\Rules\AddressBelongsToUser;
use App\Rules\PhoneNumber;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterOrderRequest extends FormRequest
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
            'address_id' => [
                'required_without_all:province,city,address,postal_code',
                'exists:addresses,id',
                new AddressBelongsToUser()
            ],
            'province' => 'required_without:address_id|string|min:2|max:100',
            'city' => 'required_without:address_id|string|min:2|max:100',
            'address' => 'required_without:address_id|string|max:255',
            'postal_code' => 'required_without:address_id|string|size:10',
            'plaque' => 'nullable|integer|max_digits:4',
            'phone' => ['nullable', 'numeric', 'size:11', new PhoneNumber()]
        ];
    }
}
