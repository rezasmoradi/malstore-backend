<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
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
            'name' => 'nullable|string|min:3|max:255',
            'model' => 'nullable|string|min:2|max:255|unique:products',
            'dimensions' => 'nullable',
            'dimensions.width' => 'nullable|numeric|max_digits:4',
            'dimensions.height' => 'nullable|numeric|max_digits:4',
            'dimensions.length' => 'nullable|numeric|max_digits:4',
            'long_desc' => 'nullable|string|unique:products',
            'short_desc' => 'nullable|string',
            'weight' => 'nullable|integer|max_digits:5',
            'display_name' => 'nullable|string|min:3|max:255|unique:products',
            'unit_price' => 'nullable|digits_between:5,9',
            'meta_description' => 'nullable|string|max:255',
            'meta_keywords' => 'nullable|string|min:3|max:255',
            'meta_keywords.*' => 'min:3',
            'meta_title' => 'nullable|string|max:120',
            'category_id' => 'nullable|exists:categories,id',
            'features' => 'nullable',
            'colors' => 'nullable|array',
            'colors.*' => 'string|size:7',
            'features.*.name' => 'nullable|string|min:2|max:255',
            'features.*.value' => 'nullable|string|min:1|max:255',
            'stock' => 'nullable|integer|min:0|max_digits:6',
            'active' => 'nullable|in:0,1',
            'images' => 'nullable|array',
            'images.*' => 'mimes:jpg,jpeg,png|max:2048',
            'tags' => 'nullable|array|min:1|max:8',
            'tags.*' => 'min:3|max:45',
            'main_photo' => 'nullable|string|'. Rule::in(collect($this->file('images'))->map(fn ($image) => $image->getClientOriginalName())->toArray()),
        ];
    }
}
