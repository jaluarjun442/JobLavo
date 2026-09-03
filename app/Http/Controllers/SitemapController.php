<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;

class SitemapController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Main Sitemap
    |--------------------------------------------------------------------------
    |
    | /sitemap.xml
    |
    | Contains:
    | - Homepage
    | - Static pages
    | - Latest jobs
    | - Contact
    | - All active category URLs
    |
    */

    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | Main / Static URLs
        |--------------------------------------------------------------------------
        */

        $urls = [

            url('/'),

            url('/latest-jobs'),

            url('/contact'),

            url('/about-us'),

            url('/privacy-policy'),

            url('/terms-and-conditions'),

            url('/disclaimer'),

        ];


        /*
        |--------------------------------------------------------------------------
        | Active Categories
        |--------------------------------------------------------------------------
        |
        | Multiple categories per post do NOT affect this.
        |
        | Category URLs are unique URLs, so each active category
        | is added only once.
        |
        */

        $categories = Category::query()

            ->where('status', true)

            ->orderBy('sort_order')

            ->orderBy('name')

            ->get([
                'slug',
            ]);


        return response()

            ->view(
                'sitemap.index',
                compact(
                    'urls',
                    'categories'
                )
            )

            ->header(
                'Content-Type',
                'application/xml'
            );
    }



    /*
    |--------------------------------------------------------------------------
    | Post Sitemap
    |--------------------------------------------------------------------------
    |
    | /sitemap-1.xml
    | /sitemap-2.xml
    | /sitemap-3.xml
    |
    | Maximum 100 posts per sitemap.
    |
    */

    public function posts($page = 1)
    {
        $page = max(
            1,
            (int) $page
        );


        /*
        |--------------------------------------------------------------------------
        | Posts Per Sitemap
        |--------------------------------------------------------------------------
        */

        $perPage = 500;


        /*
        |--------------------------------------------------------------------------
        | Published Posts
        |--------------------------------------------------------------------------
        |
        | Only currently published posts are included.
        |
        */

        $posts = Post::query()
            ->where(
                'http_status',
                200
            )
            ->where(
                'status',
                'published'
            )

            ->whereNotNull(
                'published_at'
            )

            ->where(
                'published_at',
                '<=',
                now()
            )

            ->orderBy(
                'id'
            )

            ->skip(
                ($page - 1) * $perPage
            )

            ->take(
                $perPage
            )

            ->get([
                'slug',
                'updated_at',
                'published_at',
            ]);


        /*
        |--------------------------------------------------------------------------
        | Return XML
        |--------------------------------------------------------------------------
        */

        return response()

            ->view(
                'sitemap.posts',
                compact('posts')
            )

            ->header(
                'Content-Type',
                'application/xml'
            );
    }
}
