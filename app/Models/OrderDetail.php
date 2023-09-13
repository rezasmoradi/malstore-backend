<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'guest_user_key', 'order_id', 'product_id', 'color_id', 'discount_id', 'description', 'quantity', 'unit_price'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }

    public function color()
    {
        return $this->belongsTo(ProductColor::class);
    }
}
