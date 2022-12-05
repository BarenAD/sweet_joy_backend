<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method Shop withSchedules(bool $with = false)
 *
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Shop extends Model
{
    use HasFactory;

    protected $table = 'shops';

    protected $fillable = [
        'address', 'phone', 'schedule_id', 'map_integration'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function scopeWithSchedules(Builder $query, $with = false)
    {
        if ($with) {
            return $query->with('schedule');
        }
        return $query;
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }
}
