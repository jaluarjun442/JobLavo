<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
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

    public function show(Request $request, $slug)
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
    |
    | Count only normal mobile / desktop browsers.
    | Known bots, crawlers, CLI tools and headless browsers
    | are excluded.
    |
    */

        $userAgent = $request->userAgent();

        $isBot = empty($userAgent) || preg_match(
            '/bot|crawler|spider|slurp|bingpreview|facebookexternalhit|'
                . 'linkedinbot|twitterbot|telegrambot|whatsapp|pinterest|'
                . 'google-structured-data|mediapartners-google|adsbot|'
                . 'headless|phantomjs|curl|wget|python|axios|postman|'
                . 'java|go-http-client|httpclient|scrapy|selenium|playwright/i',
            $userAgent
        );


        /*
    |--------------------------------------------------------------------------
    | Normal Browser Check
    |--------------------------------------------------------------------------
    */

        $isBrowser = preg_match(
            '/chrome|crios|firefox|fxios|safari|edg|edge|opr|opera|'
                . 'samsungbrowser|ucbrowser|android.*browser/i',
            $userAgent
        );


        /*
    |--------------------------------------------------------------------------
    | Count View
    |--------------------------------------------------------------------------
    */

        if (!$isBot && $isBrowser) {

            $blog->increment(
                'views_count'
            );
        }


        /*
    |--------------------------------------------------------------------------
    | Related / Latest Blogs
    |--------------------------------------------------------------------------
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
