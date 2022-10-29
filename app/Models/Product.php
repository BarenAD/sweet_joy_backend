<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * @method Product withCategoriesIds(bool $with = false)
 *
 * @mixin \Illuminate\Database\Eloquent\Builder
 */

class Product extends Model
{
    //use SoftDeletes;

    protected $table = 'products';

    protected $fillable = [
        'image', 'name', 'composition', 'manufacturer', 'description', 'product_unit'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function scopeWithCategoriesIds(Builder $query, bool $with = false)
    {
        if ($with) {
            return $query->with('categoriesIds');
        }
        return $query;
    }

    public function categoriesIds()
    {
        $this->hasMany(ProductCategory::class, 'product_id');
    }
}
