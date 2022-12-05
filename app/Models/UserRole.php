<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $column, $value)
 */
class UserRole extends Model
{
    use HasFactory;

    protected $table = 'user_roles';

    protected $fillable = [
        'role_id', 'user_id'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];
}
