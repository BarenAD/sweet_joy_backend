<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method DocumentLocation withDocuments(bool $with = false)
 *
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class DocumentLocation extends Model
{
    use HasFactory;

    protected $table = 'document_locations';

    protected $fillable = [
        'name', 'identify', 'document_id'
    ];

    public function scopeWithDocuments(Builder $query, $with = false)
    {
        if ($with) {
            return $query->with('document');
        }
        return $query;
    }

    public function document()
    {
        return $this->belongsTo(Document::class);
    }
}
