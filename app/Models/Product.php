<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method Product withCategoriesIds(bool $with = false)
 *
 * @mixin \Illuminate\Database\Eloquent\Builder
 */

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'image', 'name', 'composition', 'manufacturer', 'description', 'product_unit'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];
}
