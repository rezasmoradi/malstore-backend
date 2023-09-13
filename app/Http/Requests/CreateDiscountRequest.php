<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateDiscountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'customer_group_id' => 'required|exists:customer_groups,id',
            'product_id' => 'required|exists:products,id',
            'discount_value' => 'required|integer',
            'discount_unit' => 'required|string|in:currency,percent',
            'max_number_uses' => 'nullable|integer|min:1',
            'min_order_quantity' => 'nullable|integer|min:1',
            'max_discount_amount' => 'nullable|max_digits:12',
            'started_at' => 'required|date_format:Y-m-d H:i:s|after:now',
            'expired_at' => 'nullable|date_format:Y-m-d H:i:s|after:started_at',
            'active', 'required|in:0,1',
            'coupon_code' => 'nullable|string|max:45',
        ];
    }
}
