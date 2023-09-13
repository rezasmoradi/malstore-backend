<?php

namespace App\Http\Requests;

use App\Models\CustomerGroup;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateCustomerGroupRequest extends FormRequest
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
            'group_member_id' => 'nullable|exists:' . $this->group_type . ',id',
            'group_type' => 'required|in:' . implode(',', CustomerGroup::CUSTOMER_GROUPS),
            'name' => ['required', 'string', 'max:255',
                Rule::unique('customer_groups')->where(function ($query) {
                    if ($this->group_member_id) {
                        return $query->whereNot('groupable_id', null)
                            ->where('groupable_id', $this->group_member_id)
                            ->where('groupable_type', $this->group_type)
                            ->where('name', $this->name);
                    } else {
                        return $query->whereNot('groupable_id', null)
                            ->where('groupable_type', $this->group_type)
                            ->where('name', $this->name);
                    }
                })
            ]
        ];
    }
}
