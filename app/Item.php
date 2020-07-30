<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $table = 'items';

    protected $fillable = [
        'picture', 'miniature_picture', 'name', 'composition', 'manufacturer', 'description', 'product_unit'
    ];
}
