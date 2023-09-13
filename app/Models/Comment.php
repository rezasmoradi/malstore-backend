<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    const COMMENT_STATE_PENDING = 'pending';
    const COMMENT_STATE_ACCEPTED = 'accepted';
    const COMMENT_STATE_BLOCKED = 'blocked';
    const COMMENT_STATES = [self::COMMENT_STATE_PENDING, self::COMMENT_STATE_ACCEPTED, self::COMMENT_STATE_BLOCKED];

    protected $fillable = [
        'user_id', 'product_id', 'body', 'status', 'pros_cons',
    ];
}
