<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * @method Product withCategoriesIDs(bool $with = false)
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

    public function scopeWithCategoriesIDs(Builder $query, bool $with = false)
    {
        if ($with) {
            return $query->with('categoriesIDs');
        }
        return $query;
    }

    public function categoriesIDs()
    {
        $this->hasMany(ProductCategory::class, 'product_id');
    }
}
