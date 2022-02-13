<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $table = 'documents';

    protected $fillable = [
        'name', 'urn'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];
}
