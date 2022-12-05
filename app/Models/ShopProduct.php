<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopProduct extends Model
{
    use HasFactory;

    protected $table = 'shop_products';

    protected $fillable = [
        'price', 'count', 'product_id', 'shop_id'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];
}
