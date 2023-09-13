<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateSlideRequest extends FormRequest
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
            'photo' => 'required|image|mimes:jpg,jpeg,png,gif',
            'url' => 'required|string',
            'type' => 'required|in:product,category,tag',
            'first_feature' => 'required|string|max:255',
            'second_feature' => 'required|string|max:255',
            'third_feature' => 'nullable|string|max:255',
            'published_at' => 'required|date_format:Y-m-d H:i:s|after:now',
            'expired_at' => 'nullable|date_format:Y-m-d H:i:s|after:published_at',
        ];
    }
}
