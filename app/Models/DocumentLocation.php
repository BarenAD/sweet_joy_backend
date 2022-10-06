<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentLocation extends Model
{
    protected $table = 'document_locations';

    protected $fillable = [
        'name', 'identify', 'document_id'
    ];
}
