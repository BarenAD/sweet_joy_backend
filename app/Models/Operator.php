<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $column, $value)
 */
class Operator extends Model
{
    protected $table = 'operators';

    protected $fillable = [
        'user_id'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];
}
