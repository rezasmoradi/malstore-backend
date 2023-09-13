<?php

namespace App\Rules;

use App\Models\CustomerGroup;
use App\Models\Discount;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Shopper;
use App\Models\User;
use Illuminate\Contracts\Validation\Rule;

class CouponIsValid implements Rule
{
    /**
     * @var Product
     */
    private $product;

    /**
     * @var int
     */
    private $quantity;

    /**
     * Create a new rule instance.
     *
     * @param Product $product
     * @param int $quantity
     */
    public function __construct($product, $quantity = 1)
    {
        $this->product = $product;
        $this->quantity = $quantity;
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
        $discount = Discount::where('coupon_code', $value)->first();

        if ($discount && $discount->active && $discount->product_id === $this->product->id && now()->greaterThan($discount->started_at)) {
            if (now()->greaterThan($discount->discount_expired_at)) {
                $discount->active = false;
                $discount->save();
                return false;
            }
            if ($discount->minimum_order_quantity > $this->quantity) {
                return false;
            }

            if ($discount->max_number_uses && $discount->max_number_uses < OrderDetail::where('discount_id', $discount->id)->groupBy('guest_user_key')->count()) {
                return false;
            }

            $customerGroup = $discount->customerGroup;
            switch (true) {
                case $customerGroup->group === CustomerGroup::GROUP_ALL:
                case $customerGroup->group === CustomerGroup::GROUP_USERS && auth('api')->id():
                    return true;
                case $customerGroup->group === CustomerGroup::GROUP_SPECIAL:
                    if ($customerGroup->user_id) { // special discount for registered user
                        if ($customerGroup->user_id === auth('api')->id()) {
                            $discount->active = false;
                            $discount->save();
                            return true;
                        } else {
                            return false;
                        }
                    } else { // special discount for guest user
                        return OrderDetail::where(['guest_user_key' => cookie('guest_user_key'), 'discount_id' => $discount->id, 'order_id' => null])->count() == 0;
                    }
                default:
                    return false;
            }
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
        return 'The coupon code is invalid.';
    }
}
