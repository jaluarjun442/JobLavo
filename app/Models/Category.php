<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;


    protected $fillable = [

        'parent_id',

        'name',

        'slug',

        'description',

        'content',

        'seo_title',

        'meta_description',

        'meta_keywords',

        'status',

        'sort_order',

    ];


    protected $casts = [

        'status' => 'boolean',

    ];


    /*
    |--------------------------------------------------------------------------
    | Parent Category
    |--------------------------------------------------------------------------
    */

    public function parent()
    {
        return $this->belongsTo(
            Category::class,
            'parent_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Sub Categories
    |--------------------------------------------------------------------------
    */

    public function children()
    {
        return $this->hasMany(
            Category::class,
            'parent_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Posts
    |--------------------------------------------------------------------------
    */

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
