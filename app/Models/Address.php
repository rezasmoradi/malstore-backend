<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'addressable_id', 'addressable_type', 'province', 'city', 'address', 'postal_code', 'plaque'
    ];

    public function addressable()
    {
        return $this->morphTo();
    }
}
