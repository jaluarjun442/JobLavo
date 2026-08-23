<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Post;
use App\Models\Sitemap;

class SitemapService
{
    /*
    |--------------------------------------------------------------------------
    | Sync All Sitemaps
    |--------------------------------------------------------------------------
    */

    public function sync()
    {
        /*
        |--------------------------------------------------------------------------
        | Main Sitemap
        |--------------------------------------------------------------------------
        */

        $staticPages = 7;


        $categoryCount = Category::query()
            ->where('status', true)
            ->count();


        $mainUrlCount =
            $staticPages +
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
        | Only currently published posts are included.
        |
        */

        $publishedPostsQuery = Post::query()

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

        $publishedPosts = (clone $publishedPostsQuery)
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Sitemap Count
        |--------------------------------------------------------------------------
        |
        | Maximum 100 posts per sitemap.
        |
        */

        $perPage = 100;


        $sitemapCount = max(
            1,
            (int) ceil(
                $publishedPosts / $perPage
            )
        );


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

            $urlCount = (clone $publishedPostsQuery)

                ->skip(
                    ($i - 1) * $perPage
                )

                ->take(
                    $perPage
                )

                ->count();


            Sitemap::updateOrCreate(
                [
                    'filename' =>
                        'sitemap-' .
                        $i .
                        '.xml',
                ],
                [
                    'type' => 'posts',

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


        return true;
    }
}