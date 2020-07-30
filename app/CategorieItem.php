<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategorieItem extends Model
{
    protected $table = 'categories_item';

    protected $fillable = [
        'name'
    ];
}
