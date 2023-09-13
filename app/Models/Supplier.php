<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = ['first_name', 'last_name', 'company', 'phones', 'business_id'];


    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function addresses()
    {
        return $this->morphOne(Address::class, 'addressable');
    }

    public function orders()
    {
        return $this->morphOne(Order::class, 'orderable');
    }
}
