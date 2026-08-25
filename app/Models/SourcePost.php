<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SourcePost extends Model
{
    use HasFactory;

    protected $fillable = [
        'source_id',
        'source_post_id',
        'title',
        'source_url',
        'published_at',
        'is_read',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_read' => 'boolean',
    ];


    /*
    |--------------------------------------------------------------------------
    | Source
    |--------------------------------------------------------------------------
    */

    public function source()
    {
        return $this->belongsTo(
            Source::class
        );
    }
}
