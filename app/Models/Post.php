<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;


    protected $fillable = [

        /*
        |--------------------------------------------------------------------------
        | Basic
        |--------------------------------------------------------------------------
        */

        'title',

        'slug',

        'excerpt',

        'short_description',

        'content',

        'featured_image',


        /*
        |--------------------------------------------------------------------------
        | Job Details
        |--------------------------------------------------------------------------
        */

        'important_dates',

        'application_fee',

        'age_limit',

        'vacancy_details',

        'eligibility',

        'selection_process',

        'salary_details',

        'how_to_apply',

        'important_links',

        'official_website',


        /*
        |--------------------------------------------------------------------------
        | SEO
        |--------------------------------------------------------------------------
        */

        'seo_title',

        'meta_description',

        'meta_keywords',

        'canonical_url',


        /*
        |--------------------------------------------------------------------------
        | Publishing
        |--------------------------------------------------------------------------
        */

        'status',

        'published_at',

        'is_featured',

        'is_important',
        'is_indexed',
        'mobile_image',
    ];


    protected $casts = [

        'published_at' => 'datetime',

        'is_featured' => 'boolean',

        'is_important' => 'boolean',
        'is_indexed' => 'boolean',

    ];


    /*
    |--------------------------------------------------------------------------
    | Categories
    |--------------------------------------------------------------------------
    |
    | A post can belong to multiple categories.
    |
    */

    public function categories()
    {
        return $this->belongsToMany(
            Category::class,
            'category_post'
        );
    }
}
