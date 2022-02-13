<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LocationsDocuments extends Model
{
    protected $table = 'locations_documents';

    protected $fillable = [
        'name', 'identify', 'id_d'
    ];
}
