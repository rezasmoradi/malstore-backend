<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $table = 'messages';

    protected $fillable = ['name', 'email', 'body', 'mark_as_read'];

    protected $casts = [
        'mark_as_read' => 'boolean'
    ];
}
