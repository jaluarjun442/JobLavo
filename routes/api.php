<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('insta_account')->group(function () {
    Route::get('/', [ApiController::class, 'insta_account']);
    Route::get('/random', [ApiController::class, 'insta_account_random']);
});
Route::prefix('insta_10k_follow')->group(function () {
    Route::get('/', [ApiController::class, 'insta_10k_follow']);
    Route::post('/save', [ApiController::class, 'save_insta_10k_follow']);
});