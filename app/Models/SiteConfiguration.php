<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteConfiguration extends Model
{
    const FOOTER_FIRST = 'footer_first';
    const FOOTER_SECOND = 'footer_second';
    const FOOTER_THIRD = 'footer_third';
    const HEADER_LAST = 'header_last';

    use HasFactory;

    protected $table = 'site_configurations';

    protected $fillable = [
        'name', 'identify', 'value'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];
}
