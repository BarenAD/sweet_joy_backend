<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    protected $fillable = [
        'image', 'name', 'composition', 'manufacturer', 'description', 'product_unit'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];
}
