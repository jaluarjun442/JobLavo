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


        Route::post('/logout', [
            AdminController::class,
            'logout'
        ])->name('logout');
    });
Route::fallback([FrontController::class, 'noRoute']);
