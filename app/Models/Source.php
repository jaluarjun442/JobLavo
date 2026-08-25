<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SourcePost;

class Source extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'feed_url',
        'status',
        'latest_limit',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function posts()
    {
        return $this->hasMany(
            SourcePost::class,
            'source_id'
        );
    }
}
