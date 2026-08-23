<?php

use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AiJobImportController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\PostController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\FrontController;

use Illuminate\Support\Facades\Auth;

Route::get('/sitemap.xml', [
    \App\Http\Controllers\SitemapController::class,
    'index'
])->name('sitemap.index');


Route::get('/sitemap-{page}.xml', [
    \App\Http\Controllers\SitemapController::class,
    'posts'
])
    ->where('page', '[1-9][0-9]*')
    ->name('sitemap.posts');
/*
|--------------------------------------------------------------------------
| Robots.txt
|--------------------------------------------------------------------------
*/

Route::get('/robots.txt', function () {

    $lines = [
        'User-agent: *',
        'Allow: /',
        '',
        'Disallow: /admin/',
        '',
        'Sitemap: ' . url('/sitemap.xml'),
    ];

    $totalPosts = \App\Models\Post::where(
        'status',
        'published'
    )->count();

    $sitemapCount = (int) ceil($totalPosts / 100);

    for ($page = 1; $page <= $sitemapCount; $page++) {

        $lines[] =
            'Sitemap: ' .
            url('/sitemap-' . $page . '.xml');
    }

    return response(
        implode("\n", $lines) . "\n",
        200
    )->header(
        'Content-Type',
        'text/plain; charset=UTF-8'
    );
})->name('robots');

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// Auth::routes();
Auth::routes(['register' => false]);

Route::get('/', [FrontController::class, 'home'])
    ->name('home');

Route::get('/admin/ai-jobs/import', [
    AiJobImportController::class,
    'create'
])->name('admin.ai-jobs.import');

Route::post('/admin/ai-jobs/import', [
    AiJobImportController::class,
    'preview'
])->name('admin.ai-jobs.import.preview');
Route::post('/admin/ai-jobs/import/remove/{index}', [
    \App\Http\Controllers\Admin\AiJobImportController::class,
    'remove'
])->name('admin.ai-jobs.import.remove');
Route::post('/admin/ai-jobs/import/add', [
    AiJobImportController::class,
    'add'
])->name('admin.ai-jobs.import.add');


Route::post('/admin/ai-jobs/import/bulk-add', [
    AiJobImportController::class,
    'bulkAdd'
])->name('admin.ai-jobs.import.bulk-add');


Route::post('/admin/ai-jobs/import/clear', [
    \App\Http\Controllers\Admin\AiJobImportController::class,
    'clear'
])->name('admin.ai-jobs.import.clear');
/*
|--------------------------------------------------------------------------
| Category
|--------------------------------------------------------------------------
*/

Route::get('/category/{slug}', [FrontController::class, 'category'])
    ->where('slug', '[a-zA-Z0-9\-]+')
    ->name('category');

Route::get(
    '/latest-jobs',
    [FrontController::class, 'latestJobs']
)->name('latest.jobs');

Route::get('/contact', [FrontController::class, 'contact'])
    ->name('contact');


Route::post('/contact', [FrontController::class, 'contactSubmit'])
    ->name('contact.submit');

/*
|--------------------------------------------------------------------------
| Single Post
|--------------------------------------------------------------------------
*/

Route::get('/post/{slug}', [FrontController::class, 'post'])
    ->where('slug', '[a-zA-Z0-9\-]+')
    ->name('post');

Route::get('/about-us', function () {
    return app(\App\Http\Controllers\FrontController::class)
        ->staticPage('about-us');
})->name('about');


Route::get('/privacy-policy', function () {
    return app(\App\Http\Controllers\FrontController::class)
        ->staticPage('privacy-policy');
})->name('privacy');


Route::get('/terms-and-conditions', function () {
    return app(\App\Http\Controllers\FrontController::class)
        ->staticPage('terms-and-conditions');
})->name('terms');


Route::get('/disclaimer', function () {
    return app(\App\Http\Controllers\FrontController::class)
        ->staticPage('disclaimer');
})->name('disclaimer');

/*
|--------------------------------------------------------------------------
| Search
|--------------------------------------------------------------------------
*/
Route::get(
    '/search',
    [FrontController::class, 'search']
)->name('search');
/*
|--------------------------------------------------------------------------
| 404
|--------------------------------------------------------------------------
|
| Keep this route at the very bottom.
|
*/

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/', [
            AdminController::class,
            'index'
        ])->name('dashboard');


        Route::get('/categories/data', [
            CategoryController::class,
            'data'
        ])->name('categories.data');


        Route::resource(
            'categories',
            CategoryController::class
        )->except([
            'show'
        ]);
        Route::get('/category/{slug}', [
            FrontController::class,
            'category'
        ])
            ->where('slug', '[a-zA-Z0-9\-]+')
            ->name('category');

        Route::resource(
            'posts',
            PostController::class
        )->except([
            'show'
        ]);


        Route::get('/posts/data', [
            PostController::class,
            'data'
        ])->name('posts.data');

        /*
|--------------------------------------------------------------------------
| Sitemaps
|--------------------------------------------------------------------------
*/

        Route::get('/sitemaps', [
            \App\Http\Controllers\Admin\SitemapController::class,
            'index'
        ])->name('sitemaps.index');


        Route::get('/sitemaps/{sitemap}/edit', [
            \App\Http\Controllers\Admin\SitemapController::class,
            'edit'
        ])->name('sitemaps.edit');


        Route::put('/sitemaps/{sitemap}', [
            \App\Http\Controllers\Admin\SitemapController::class,
            'update'
        ])->name('sitemaps.update');
        Route::post('/sitemaps/refresh', [
            \App\Http\Controllers\Admin\SitemapController::class,
            'refresh'
        ])->name('sitemaps.refresh');
        Route::post('/logout', [
            AdminController::class,
            'logout'
        ])->name('logout');
    });
Route::fallback([FrontController::class, 'noRoute']);
