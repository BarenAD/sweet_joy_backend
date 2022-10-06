<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    protected $table = 'shops';

    protected $fillable = [
        'address', 'phone', 'schedule_id', 'map_integration'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];
}
