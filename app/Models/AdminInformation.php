<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminInformation extends Model
{
    protected $table = 'admins_information';

    protected $fillable = [
        'id_ar', 'id_pos', 'id_u'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];
}
