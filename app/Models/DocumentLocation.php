<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentLocation extends Model
{
    use HasFactory;

    protected $table = 'document_locations';

    protected $fillable = [
        'name', 'identify', 'document_id'
    ];
}
