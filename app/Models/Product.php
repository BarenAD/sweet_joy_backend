<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Product
 * @package App\Models
 *
 * @method Product withCategories(bool $with = false)
 *
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

    public function scopeWithCategories(Builder $query, bool $with = false)
    {
        if ($with) {
            return $query->with('categories');
        }
        return $query;
    }

    public function categories()
    {
        return $this->hasMany(ProductCategory::class, 'product_id');
    }
}
