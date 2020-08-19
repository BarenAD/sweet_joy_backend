<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $table = 'items';

    protected $fillable = [
        'picture', 'miniature_picture', 'name', 'composition', 'manufacturer', 'description', 'product_unit'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];
}
