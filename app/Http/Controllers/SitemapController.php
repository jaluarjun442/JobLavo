<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Main Sitemap
    |--------------------------------------------------------------------------
    */

    public function index()
    {
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
    | Categories
    |--------------------------------------------------------------------------
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
                compact('urls', 'categories')
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
    */

    public function posts($page = 1)
    {
        $page = max(1, (int) $page);

        $posts = Post::query()

            ->where('status', 'published')

            ->orderBy('id')

            ->skip(($page - 1) * 100)

            ->take(100)

            ->get([
                'slug',
                'updated_at',
                'published_at',
            ]);


        return response()
            ->view('sitemap.posts', compact('posts'))
            ->header('Content-Type', 'application/xml');
    }
}
