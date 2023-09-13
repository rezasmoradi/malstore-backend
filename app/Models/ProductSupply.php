<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSupply extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'supplier_id', 'quantity', 'unit_price', 'shipping_cost'
    ];
}
