<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $column, $value)
 */
class UserRole extends Model
{
    protected $table = 'user_roles';

    protected $fillable = [
        'role_id', 'user_id'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];
}
