<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateProductRequest extends FormRequest
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
            'name' => 'required|string|min:3|max:255',
            'model' => 'required|string|min:2|max:255|unique:products',
            'dimensions' => 'required',
            'dimensions.width' => 'required|numeric|max_digits:4',
            'dimensions.length' => 'required|numeric|max_digits:4',
            'dimensions.height' => 'nullable|numeric|max_digits:4',
            'long_desc' => 'required|string|max:1000',
            'short_desc' => 'nullable|string|max:500',
            'weight' => 'required|integer|max_digits:5',
            'display_name' => 'required|string|min:3|max:255|unique:products',
            'unit_price' => 'required|digits_between:5,9',
            'category_id' => 'required|exists:categories,id',
            'features' => 'required',
            'features.*.name' => 'required|string|min:2|max:255',
            'features.*.value' => 'required|string|min:1|max:255',
            'best_features' => 'required',
            'best_features.*.name' => 'required|string|min:2|max:255',
            'best_features.*.value' => 'required|string|min:1|max:255',
            'active' => 'required|in:0,1',
            'tags' => 'required|array|min:1|max:8',
            'tags.*' => 'required|min:3|max:45',
            'images' => 'required|array',
            'images.*.file' => 'mimes:jpg,jpeg,png|max:2048',
            'images.*.main' => 'required|in:0,1',
            'colors' => 'required|array',
            'colors.*.name' => 'required|string|max:255',
            'colors.*.code' => 'required|string|size:7',
            'colors.*.stock' => 'required|integer|min:0|max_digits:6',
            'meta_title' => 'required|string|max:120',
            'meta_description' => 'required|string|max:255',
            'meta_keywords' => 'required|array|min:2|max:255',
            'meta_keywords.*' => 'min:3',
        ];
    }
}
