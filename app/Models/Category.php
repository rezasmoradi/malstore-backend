<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'url_name', 'thumbnail', 'image', 'parent_id', 'return_conditions'];

    public function products()
    {
        return $this->hasMany(Product::class)->take(20);
    }

    public function subCategories()
    {
        return $this->hasMany(static::class, 'parent_id');
    }

    public function parentCategory()
    {
        return $this->belongsTo(static::class, 'parent_id');
    }
}
