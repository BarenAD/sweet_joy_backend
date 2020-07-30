<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InformationCategorieItem extends Model
{
    protected $table = 'information_categories_items';

    protected $fillable = [
        'id_i', 'id_ci'
    ];
}
