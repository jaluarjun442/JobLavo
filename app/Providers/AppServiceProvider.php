<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use App\Models\Category;
use App\Models\Post;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }


    public function boot()
    {
        /*
        |--------------------------------------------------------------------------
        | Force HTTPS
        |--------------------------------------------------------------------------
        */

        if (
            env('APP_URL') &&
            strpos(env('APP_URL'), 'https://') === 0
        ) {
            URL::forceScheme('https');
        }


        /*
        |--------------------------------------------------------------------------
        | Bootstrap Pagination
        |--------------------------------------------------------------------------
        */

        Paginator::useBootstrap();


        /*
        |--------------------------------------------------------------------------
        | Header + Sidebar Categories
        |--------------------------------------------------------------------------
        */

        View::composer('layouts.web', function ($view) {

            $headerCategories = Cache::remember(
                'header_categories',
                now()->addMinutes(2880),
                function () {

                    return Category::query()
                        ->where('status', true)
                        ->where('display_header', true)
                        ->orderBy('sort_order')
                        ->orderBy('name')
                        ->get();
                }
            );

            $view->with('headerCategories', $headerCategories);
        });

        /*
        |--------------------------------------------------------------------------
        | Sidebar
        |--------------------------------------------------------------------------
        */

        View::composer('layouts.partials.sidebar', function ($view) {

            $sidebarCategories = Cache::remember(
                'sidebar_categories',
                now()->addMinutes(2880),
                function () {

                    return Category::query()
                        ->with([
                            'children' => function ($query) {

                                $query
                                    ->where('status', true)
                                    ->orderBy('sort_order')
                                    ->orderBy('name');

                            }
                        ])
                        ->where('status', true)
                        ->whereNull('parent_id')
                        ->orderBy('sort_order')
                        ->orderBy('name')
                        ->get();
                }
            );

            $sidebarLatestPosts = Cache::remember(
                'sidebar_latest_posts',
                now()->addMinutes(2880),
                function () {

                    return Post::query()
                        ->where('http_status', 200)
                        ->where('status', 'published')
                        ->whereNotNull('published_at')
                        ->where('published_at', '<=', now())
                        ->latest('published_at')
                        ->take(8)
                        ->get();
                }
            );

            $view->with([
                'sidebarCategories' => $sidebarCategories,
                'sidebarLatestPosts' => $sidebarLatestPosts,
            ]);
        });
    }
}
