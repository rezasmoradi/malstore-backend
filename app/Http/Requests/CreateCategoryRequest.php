<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateCategoryRequest extends FormRequest
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
            'name' => 'required|string|min:3|max:45|unique:categories',
            'url_name' => 'required|string|min:3|max:45|unique:categories',
            'thumbnail' => 'nullable|mimes:png,jpg,jpeg|max:1024',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'return_condition' => 'nullable|string|min:50|max:255',
            'parent_id' => 'nullable|exists:categories,id'
        ];
    }
}
