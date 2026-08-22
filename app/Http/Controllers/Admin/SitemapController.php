<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Sitemap;
use Illuminate\Http\Request;

class SitemapController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Sitemap Dashboard
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        /*
    |--------------------------------------------------------------------------
    | Main Sitemap
    |--------------------------------------------------------------------------
    */

        $mainUrlCount =
            7 +
            \App\Models\Category::where('status', true)->count();


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
    | Published Posts
    |--------------------------------------------------------------------------
    */

        $publishedPosts = Post::where(
            'status',
            'published'
        )->count();


        $postSitemapCount = (int) ceil(
            $publishedPosts / 100
        );


        /*
    |--------------------------------------------------------------------------
    | Create / Update Post Sitemaps
    |--------------------------------------------------------------------------
    */

        for ($i = 1; $i <= $postSitemapCount; $i++) {

            $urlCount = Post::where(
                'status',
                'published'
            )
                ->skip(($i - 1) * 100)
                ->take(100)
                ->count();


            Sitemap::updateOrCreate(
                [
                    'filename' => 'sitemap-' . $i . '.xml',
                ],
                [
                    'type' => 'posts',
                    'url_count' => $urlCount,
                ]
            );
        }


        /*
    |--------------------------------------------------------------------------
    | Remove Sitemap Records Which Are No Longer Needed
    |--------------------------------------------------------------------------
    */

        Sitemap::where('type', 'posts')
            ->where('filename', 'like', 'sitemap-%.xml')
            ->whereNotIn(
                'filename',
                collect(range(1, max(1, $postSitemapCount)))
                    ->map(function ($number) {
                        return 'sitemap-' . $number . '.xml';
                    })
                    ->toArray()
            )
            ->delete();


        /*
    |--------------------------------------------------------------------------
    | Get Sitemaps
    |--------------------------------------------------------------------------
    */

        $sitemaps = Sitemap::orderByRaw("
        CASE
            WHEN type = 'main' THEN 0
            ELSE 1
        END
    ")
            ->orderBy('filename')
            ->get();


        return view(
            'admin.sitemaps.index',
            compact('sitemaps')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Edit
    |--------------------------------------------------------------------------
    */

    public function edit(Sitemap $sitemap)
    {
        return view(
            'admin.sitemaps.edit',
            compact('sitemap')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Update Status
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        Sitemap $sitemap
    ) {
        $validated = $request->validate([

            'status' => [
                'required',
                'in:not_submitted,submitted,processing,indexed,error',
            ],

            'submitted_at' => [
                'nullable',
                'date',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:2000',
            ],

        ]);


        if ($validated['status'] === 'submitted') {

            $validated['submitted_at'] =
                $validated['submitted_at'] ?? now();
        } elseif ($validated['status'] === 'not_submitted') {

            $validated['submitted_at'] = null;
        }

        $sitemap->update($validated);


        return redirect()
            ->route('admin.sitemaps.index')
            ->with(
                'success',
                'Sitemap status updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Main Sitemap URL Count
    |--------------------------------------------------------------------------
    */

    private function mainUrlCount()
    {
        $staticPages = 7;

        $categories = \App\Models\Category::where(
            'status',
            true
        )->count();

        return $staticPages + $categories;
    }
    public function refresh()
    {
        $publishedPosts = Post::where(
            'status',
            'published'
        )->count();

        $sitemapCount = max(
            1,
            (int) ceil($publishedPosts / 100)
        );

        /*
    |--------------------------------------------------------------------------
    | Main Sitemap
    |--------------------------------------------------------------------------
    */

        $mainUrlCount =
            7 +
            \App\Models\Category::where(
                'status',
                true
            )->count();

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
    | Post Sitemaps
    |--------------------------------------------------------------------------
    */

        for ($i = 1; $i <= $sitemapCount; $i++) {

            $count = Post::where(
                'status',
                'published'
            )
                ->skip(($i - 1) * 100)
                ->take(100)
                ->count();

            Sitemap::updateOrCreate(
                [
                    'filename' => 'sitemap-' . $i . '.xml',
                ],
                [
                    'type' => 'posts',
                    'url_count' => $count,
                ]
            );
        }


        /*
    |--------------------------------------------------------------------------
    | Delete Old Sitemap Records
    |--------------------------------------------------------------------------
    */

        Sitemap::where('type', 'posts')
            ->where('filename', 'like', 'sitemap-%.xml')
            ->whereNotIn(
                'filename',
                collect(range(1, $sitemapCount))
                    ->map(function ($number) {
                        return 'sitemap-' . $number . '.xml';
                    })
                    ->toArray()
            )
            ->delete();


        return redirect()
            ->route('admin.sitemaps.index')
            ->with(
                'success',
                'Sitemaps refreshed successfully.'
            );
    }
}
