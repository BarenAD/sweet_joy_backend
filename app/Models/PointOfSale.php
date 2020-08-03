<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PointOfSale extends Model
{
    protected $table = 'points_of_sale';

    protected $fillable = [
        'address', 'phone', 'id_s'
    ];
}
