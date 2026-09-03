<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model
{
    use HasFactory;


    /*
    |--------------------------------------------------------------------------
    | Table
    |--------------------------------------------------------------------------
    */

    protected $table = 'blog_posts';


    /*
    |--------------------------------------------------------------------------
    | Fillable
    |--------------------------------------------------------------------------
    */

    protected $fillable = [

        'title',

        'slug',

        'desktop_image',

        'mobile_image',

        'content',

        'seo_title',

        'meta_description',

        'meta_keywords',

        'canonical_url',

        'views_count',

        'published_date',

        'published_by',

    ];


    /*
    |--------------------------------------------------------------------------
    | Casts
    |--------------------------------------------------------------------------
    */

    protected $casts = [

        'published_date' => 'datetime',

        'views_count' => 'integer',

    ];
}
