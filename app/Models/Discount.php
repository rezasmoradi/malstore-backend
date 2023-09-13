<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Discount extends Model
{
    use HasFactory, SoftDeletes;

    const DISCOUNT_UNIT_PERCENT = 'percent';
    const DISCOUNT_UNIT_CURRENCY = 'currency';
    const DISCOUNT_UNITS = [self::DISCOUNT_UNIT_PERCENT, self::DISCOUNT_UNIT_CURRENCY];

    protected $fillable = [
        'customer_group_id', 'product_id', 'discount_value', 'discount_unit', 'max_number_uses', 'min_order_quantity',
        'max_discount_amount', 'started_at', 'expired_at', 'coupon_code', 'active'
    ];

    public function customerGroup()
    {
        return $this->belongsTo(CustomerGroup::class, 'customer_group_id');
    }

    protected $casts = [
        'max_number_uses' => 'integer',
        'started_at' => 'datetime',
        'expired_at' => 'datetime',
        'active' => 'boolean',
    ];
}
