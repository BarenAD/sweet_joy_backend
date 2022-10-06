<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopAssortment extends Model
{
    protected $table = 'shop_assortment';

    protected $fillable = [
        'price', 'count', 'product_id', 'shop_id'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];
}
