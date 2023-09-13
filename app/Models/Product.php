<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'model', 'width', 'length', 'height', 'long_desc', 'short_desc', 'weight', 'display_name', 'category_id',
        'unit_price', 'slug', 'meta_description', 'meta_keywords', 'meta_title', 'active'
    ];

    protected $casts = [
        'width' => 'integer',
        'length' => 'integer',
        'height' => 'integer',
        'weight' => 'integer',
        'features' => 'array'
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }

    public function features()
    {
        return $this->hasMany(ProductFeature::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function rating()
    {
        return $this->hasMany(Rating::class, 'product_id');
    }

    public function colors()
    {
        return $this->hasMany(ProductColor::class, 'product_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'product_tags', 'product_id', 'tag_id');
    }
}
