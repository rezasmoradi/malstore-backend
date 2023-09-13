<?php

namespace App\Http\Requests;

use App\Models\OrderDetail;
use Illuminate\Foundation\Http\FormRequest;

class DeleteOrderProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->order_detail->guest_user_key === cookie(env('GUEST_USER_KEY', '__guest_user_key'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
