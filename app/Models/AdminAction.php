<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminAction extends Model
{
    protected $table = 'admin_actions';

    protected $fillable = [
        'id', 'name', 'description', 'created_at', 'updated_at'
    ];
}
