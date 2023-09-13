<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductQuestion extends Model
{
    use HasFactory;

    const QUESTION_STATE_PENDING = 'pending';
    const QUESTION_STATE_ACCEPTED = 'accepted';
    const QUESTION_STATE_BLOCKED = 'blocked';
    const QUESTION_STATES = [self::QUESTION_STATE_PENDING, self::QUESTION_STATE_ACCEPTED, self::QUESTION_STATE_BLOCKED];

    protected $fillable = [
        'user_id', 'product_id', 'body', 'status', 'reply_to'
    ];

    public function replies()
    {
        return $this->hasMany(static::class, 'reply_to');
    }
}
