<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderReturn extends Model
{
    use HasFactory, SoftDeletes;

    const ORDER_RETURN_STATE_RETURNING = 'returning';
    const ORDER_RETURN_STATE_RETURNED = 'returned';
    const ORDER_RETURN_STATE_CANCELED = 'canceled';
    const ORDER_RETURN_STATES = [self::ORDER_RETURN_STATE_RETURNING, self::ORDER_RETURN_STATE_RETURNED, self::ORDER_RETURN_STATE_CANCELED];

    protected $fillable = [
        'order_id', 'reason', 'description', 'status'
    ];
}
