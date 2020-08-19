<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuperAdmin extends Model
{
    protected $table = 'super_admins';

    protected $fillable = [
        'id_u'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];
}
