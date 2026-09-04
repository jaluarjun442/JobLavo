<?php

use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AiJobImportController;
use App\Http\Controllers\Admin\BlogPostController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\SourceController;
use App\Http\Controllers\Admin\SourcePostController;
use App\Http\Controllers\BlogController;
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

Route::get('/sitemap-blogs.xml', [
    \App\Http\Controllers\SitemapController::class,
    'blogs'
])->name('sitemap.blogs');

Route::get('/cron/google-indexing', [
    PostController::class,
    'googleIndexingCron'
]);
Route::get(
    '/cron/source-fetch',
    [SourceController::class, 'fetchAll']
)->name('cron.source-fetch');
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


Route::get(
    '/blog',
    [BlogController::class, 'index']
)->name('blog.index');


Route::get(
    '/blog/{slug}',
    [BlogController::class, 'show']
)
    ->where(
        'slug',
        '[a-zA-Z0-9\-]+'
    )
    ->name('blog.show');

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

Route::get('/author/manisha-jalu', function () {
    return app(\App\Http\Controllers\FrontController::class)
        ->author('manisha-jalu');
})->name('manisha_jalu');
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

Route::get('/cookie-policy', function () {
    return view('pages.cookie-policy');
})->name('cookie.policy');
Route::get('/about', function () {
    return redirect()->route('about', [], 301);
});
Route::get('/contact-us', function () {
    return redirect()->route('contact', [], 301);
});
Route::get('/privacy', function () {
    return redirect()->route('privacy', [], 301);
});
Route::get('/terms', function () {
    return redirect()->route('terms', [], 301);
});
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
        Route::post('/posts/add', [
            PostController::class,
            'add'
        ])->name('posts.add');


        Route::post('/posts/bulk-add', [
            PostController::class,
            'bulkAdd'
        ])->name('posts.bulk-add');

        Route::post(
            '/posts/index-pending',
            [PostController::class, 'indexPendingPosts']
        )->name('posts.index-pending');
        Route::post(
            '/posts/{post}/index',
            [PostController::class, 'indexPost']
        )->name('posts.index-post');

        // source route
        Route::get(
            '/sources',
            [SourceController::class, 'index']
        )->name('sources.index');
        Route::post(
            '/sources',
            [SourceController::class, 'store']
        )->name('sources.store');
        Route::put(
            '/sources/{source}',
            [SourceController::class, 'update']
        )->name('sources.update');
        Route::delete(
            '/sources/{source}',
            [SourceController::class, 'destroy']
        )->name('sources.destroy');
        Route::post(
            '/sources/{source}/fetch',
            [SourceController::class, 'fetchNow']
        )->name('sources.fetch');
        Route::get(
            '/sources/{source}/posts/{status}',
            [SourcePostController::class, 'index']
        )->where(
            'status',
            'read|unread'
        )->name('sources.posts');
        Route::post(
            '/source-posts/mark-read',
            [SourcePostController::class, 'markRead']
        )->name('source-posts.mark-read');
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

        /*
|--------------------------------------------------------------------------
| Logs
|--------------------------------------------------------------------------
*/

        Route::get('/logs', [
            \App\Http\Controllers\Admin\LogController::class,
            'index'
        ])->name('logs.index');


        Route::get('/logs/download', [
            \App\Http\Controllers\Admin\LogController::class,
            'download'
        ])->name('logs.download');


        Route::post('/logs/clear', [
            \App\Http\Controllers\Admin\LogController::class,
            'clear'
        ])->name('logs.clear');



        Route::resource(
            'blog',
            BlogPostController::class
        )->except([
            'show'
        ]);
        Route::get('/blog/data', [
            BlogPostController::class,
            'data'
        ])->name('blog.data');


        Route::post('/logout', [
            AdminController::class,
            'logout'
        ])->name('logout');
    });

Route::fallback([FrontController::class, 'noRoute']);
