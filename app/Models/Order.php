<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    const ORDER_STATE_PROCESSING = 'processing';
    const ORDER_STATE_DELIVERED = 'delivered';
    const ORDER_STATE_CANCELED = 'canceled';
    const ORDER_STATES = [self::ORDER_STATE_PROCESSING, self::ORDER_STATE_DELIVERED, self::ORDER_STATE_CANCELED];

    protected $fillable = [
        'orderable_id', 'orderable_type', 'address_delivery_id', 'phone', 'total_payment', 'status', 'paid'
    ];

    public function orderable()
    {
        return $this->morphTo();
    }

    public function details()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function shipper()
    {
        return $this->hasOne(OrderShipping::class);
    }

    public function setPhoneAttribute()
    {
        if ($this->phone) {
            $this->attributes['phone'] = '+98' . substr($this->phone, 0, 1);
        }

        return null;
    }
}
