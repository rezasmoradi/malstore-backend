<?php

namespace App\Rules;

use App\Models\Address;
use App\Models\User;
use Illuminate\Contracts\Validation\Rule;

class AddressBelongsToUser implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        foreach (auth()->user()->addresses as $address) {
            if ($address->id === $value) return true;
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The address does not belong to user';
    }
}
