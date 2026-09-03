<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Post;
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
            ->where('status', true)
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
                'url_count' => $mainUrlCount,
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | Published Posts Query
        |--------------------------------------------------------------------------
        |
        | Only posts which are currently published are included.
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
        | Maximum 100 published posts per sitemap.
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
        |
        | If there are no published posts, remove all post sitemap
        | records instead of creating an empty sitemap-1.xml.
        |
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
                |
                | Example:
                |
                | 114 posts
                |
                | Sitemap 1 = 100
                | Sitemap 2 = 14
                |
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
                        max(0, $remaining)
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
