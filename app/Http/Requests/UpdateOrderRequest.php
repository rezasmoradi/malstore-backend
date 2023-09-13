<?php

namespace App\Http\Requests;

use App\Models\OrderDetail;
use App\Models\ProductColor;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return OrderDetail::query()->where(['guest_user_key' => cookie(env('GUEST_USER_KEY', '__guest_user_key')), 'order_id' => $this->order_id])->exists();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        var_dump(ProductColor::query()->where('product_id', $this->product->id)->get(['id'])->toArray());
        return [
            'color_id' => ['nullable', Rule::in(ProductColor::query()->where('product_id', $this->product->id)->get(['id'])->toArray())],
            'quantity' => 'nullable|integer|max:100',
            'description' => 'nullable|string|max:1000',
        ];
    }
}
