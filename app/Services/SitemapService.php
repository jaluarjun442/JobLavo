<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Post;
use App\Models\Blog;
use App\Models\BlogPost;
use App\Models\Sitemap;

class SitemapService
{
    /*
    |--------------------------------------------------------------------------
    | Sitemap Configuration
    |--------------------------------------------------------------------------
    */

    private const POSTS_PER_SITEMAP = 500;

    private const STATIC_PAGE_COUNT = 7;


    /*
    |--------------------------------------------------------------------------
    | Sync All Sitemaps
    |--------------------------------------------------------------------------
    */

    public function sync(): bool
    {
        /*
        |--------------------------------------------------------------------------
        | Main Sitemap
        |--------------------------------------------------------------------------
        */

        $categoryCount = Category::query()

            ->where(
                'status',
                true
            )

            ->count();


        $mainUrlCount =
            self::STATIC_PAGE_COUNT +
            $categoryCount;


        Sitemap::updateOrCreate(
            [
                'filename' => 'sitemap.xml',
            ],
            [
                'type' => 'main',

                'url_count' =>
                $mainUrlCount,
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | Published Posts Query
        |--------------------------------------------------------------------------
        |
        | Only posts with:
        |
        | http_status = 200
        | status = published
        | published_at is set
        | published_at <= now
        |
        */

        $publishedPostsQuery = Post::query()

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
            );

        /*
        |--------------------------------------------------------------------------
        | Published Blogs
        |--------------------------------------------------------------------------
        |
        | Blog sitemap is separate from job post sitemaps.
        |
        */

        $publishedBlogs = BlogPost::query()

            ->whereNotNull(
                'published_date'
            )

            ->where(
                'published_date',
                '<=',
                now()
            )

            ->count();


        /*
        |--------------------------------------------------------------------------
        | Blog Sitemap
        |--------------------------------------------------------------------------
        */

        Sitemap::updateOrCreate(
            [
                'filename' =>
                'sitemap-blogs.xml',
            ],
            [
                'type' =>
                'blogs',

                'url_count' =>
                $publishedBlogs,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Published Post Count
        |--------------------------------------------------------------------------
        */

        $publishedPosts =
            (clone $publishedPostsQuery)
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Sitemap Count
        |--------------------------------------------------------------------------
        |
        | Maximum 500 posts per sitemap.
        |
        */

        $sitemapCount =
            (int) ceil(
                $publishedPosts /
                    self::POSTS_PER_SITEMAP
            );


        /*
        |--------------------------------------------------------------------------
        | No Published Posts
        |--------------------------------------------------------------------------
        */

        if ($sitemapCount === 0) {

            Sitemap::query()

                ->where(
                    'type',
                    'posts'
                )

                ->where(
                    'filename',
                    'like',
                    'sitemap-%.xml'
                )

                ->delete();
        } else {

            /*
            |--------------------------------------------------------------------------
            | Create / Update Post Sitemaps
            |--------------------------------------------------------------------------
            */

            for (
                $i = 1;
                $i <= $sitemapCount;
                $i++
            ) {

                /*
                |--------------------------------------------------------------------------
                | Calculate URLs In This Sitemap
                |--------------------------------------------------------------------------
                */

                $offset =
                    ($i - 1) *
                    self::POSTS_PER_SITEMAP;


                $remaining =
                    $publishedPosts -
                    $offset;


                $urlCount =
                    min(
                        self::POSTS_PER_SITEMAP,
                        max(
                            0,
                            $remaining
                        )
                    );


                Sitemap::updateOrCreate(
                    [
                        'filename' =>
                        'sitemap-' .
                            $i .
                            '.xml',
                    ],
                    [
                        'type' =>
                        'posts',

                        'url_count' =>
                        $urlCount,
                    ]
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Delete Old Post Sitemap Records
            |--------------------------------------------------------------------------
            */

            $validFilenames = collect(
                range(
                    1,
                    $sitemapCount
                )
            )

                ->map(
                    function ($number) {

                        return
                            'sitemap-' .
                            $number .
                            '.xml';
                    }
                )

                ->toArray();


            Sitemap::query()

                ->where(
                    'type',
                    'posts'
                )

                ->where(
                    'filename',
                    'like',
                    'sitemap-%.xml'
                )

                ->whereNotIn(
                    'filename',
                    $validFilenames
                )

                ->delete();
        }




        /*
        |--------------------------------------------------------------------------
        | Finished
        |--------------------------------------------------------------------------
        */

        return true;
    }
}
