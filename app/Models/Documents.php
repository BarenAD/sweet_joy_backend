<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Documents extends Model
{
    protected $table = 'documents';

    protected $fillable = [
        'name', 'uri'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];
}
