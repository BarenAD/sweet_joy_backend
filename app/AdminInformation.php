<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdminInformation extends Model
{
    protected $table = 'admins_information';

    protected $fillable = [
        'id_ar', 'id_pos', 'id_u'
    ];
}
