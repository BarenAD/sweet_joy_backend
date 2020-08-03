<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductInformation extends Model
{
    protected $table = 'products_information';

    protected $fillable = [
        'price', 'count', 'id_i', 'id_pos'
    ];
}
