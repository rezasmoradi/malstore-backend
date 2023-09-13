<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name'];

    protected function suppliers()
    {
        return $this->belongsToMany(Supplier::class, 'suppliers');
    }
}
