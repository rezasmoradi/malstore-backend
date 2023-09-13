<?php

namespace App\Http\Requests;

use App\Rules\CouponIsValid;
use Illuminate\Foundation\Http\FormRequest;

class CreateOrderRequest extends FormRequest
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
            'quantity' => 'nullable|integer|max:100',
            'color_id' => 'required|exists:product_colors,id',
            'description' => 'nullable|string|max:1000',
            'coupon_code' => ['nullable', 'string', 'min:', 'max:10', new CouponIsValid($this->product, $this->quantity)],
        ];
    }
}
