<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
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
            'name' => 'nullable|string|min:3|max:45',
            'thumbnail' => 'nullable|mimes:svg|max:1024',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'parent_id' => 'nullable|exists:categories,id'
        ];
    }
}
