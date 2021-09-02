<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteConfigurations extends Model
{
    protected $table = 'site_configurations';

    protected $fillable = [
        'name', 'identify', 'value'
    ];
}
