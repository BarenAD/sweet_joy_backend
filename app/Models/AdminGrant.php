<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminGrant extends Model
{
    protected $table = 'admin_grants';

    protected $fillable = [
        'id_ar', 'id_aa'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];
}
