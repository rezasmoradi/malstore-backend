<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerGroup extends Model
{
    use HasFactory, SoftDeletes;

    const GROUP_ALL = 'all';
    const GROUP_USERS = 'users';
    const GROUP_SHOPPERS = 'shoppers';
    const GROUP_SPECIAL = 'special';
    const CUSTOMER_GROUPS = [self::GROUP_ALL, self::GROUP_USERS, self::GROUP_SHOPPERS, self::GROUP_SPECIAL];

    protected $fillable = ['groupable_id', 'groupable_type', 'name'];
}
