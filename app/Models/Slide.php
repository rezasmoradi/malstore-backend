<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Slide extends Model
{
    use HasFactory, SoftDeletes;

    const URL_TYPE_PRODUCT = 'product';
    const URL_TYPE_CATEGORY = 'category';
    const URL_TYPE_TAG = 'tag';
    const URL_TYPES = [self::URL_TYPE_PRODUCT, self::URL_TYPE_CATEGORY, self::URL_TYPE_TAG];

    protected $fillable = [
        'photo', 'url', 'type', 'first_feature', 'second_feature', 'third_feature', 'published_at', 'expired_at'
    ];
}
