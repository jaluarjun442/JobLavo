<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;

class BlogController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Blog Listing
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $blogs = BlogPost::query()

            ->whereNotNull(
                'published_date'
            )

            ->where(
                'published_date',
                '<=',
                now()
            )

            ->latest(
                'published_date'
            )

            ->paginate(12);


        return view(
            'blog.index',
            compact('blogs')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Single Blog
    |--------------------------------------------------------------------------
    */

    public function show($slug)
    {
        $blog = BlogPost::query()

            ->where(
                'slug',
                $slug
            )

            ->whereNotNull(
                'published_date'
            )

            ->where(
                'published_date',
                '<=',
                now()
            )

            ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | Views Count
        |--------------------------------------------------------------------------
        */

        $blog->increment(
            'views_count'
        );


        /*
        |--------------------------------------------------------------------------
        | Related / Latest Blogs
        |--------------------------------------------------------------------------
        |
        | Since blog categories are not being used,
        | simply show latest blogs excluding current blog.
        |
        */

        $relatedBlogs = BlogPost::query()

            ->where(
                'id',
                '!=',
                $blog->id
            )

            ->whereNotNull(
                'published_date'
            )

            ->where(
                'published_date',
                '<=',
                now()
            )

            ->latest(
                'published_date'
            )

            ->take(5)

            ->get();


        return view(
            'blog.show',
            compact(
                'blog',
                'relatedBlogs'
            )
        );
    }
}