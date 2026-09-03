<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use App\Models\Category;

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

        View::composer(
            [
                'layouts.web',
                'layouts.partials.sidebar',
            ],
            function ($view) {

                $headerCategories = Category::query()

                    ->where('status', true)

                    ->where('display_header', true)

                    // ->whereNull('parent_id')

                    ->orderBy('sort_order')

                    ->orderBy('name')

                    ->get();

                $sidebarCategories = Category::query()

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

                $sidebarLatestPosts = \App\Models\Post::query()

                    ->where('http_status', 200)
                    ->where('status', 'published')

                    ->whereNotNull('published_at')

                    ->where(
                        'published_at',
                        '<=',
                        now()
                    )

                    ->latest('published_at')

                    ->take(8)

                    ->get();
                $view->with([
                    'headerCategories' => $headerCategories,
                    'sidebarCategories' => $sidebarCategories,
                    'sidebarLatestPosts' => $sidebarLatestPosts,
                ]);
            }
        );
    }
}
