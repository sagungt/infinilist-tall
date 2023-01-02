<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChapterController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ShortenerController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('auth')
    ->controller(AuthController::class)
    ->group(function () {
        Route::post('/login', 'login');
        Route::post('/logout', 'logout');
    });

Route::prefix('users')
    ->controller(UserController::class)
    ->group(function () {
        Route::get('/', 'show');
    });

Route::prefix('posts')
    ->controller(PostController::class)
    ->group(function () {
        Route::get('/', 'index');
        // Route::post('/store', 'store');
        Route::get('/my', 'postsByOwner')->middleware('cookie.token');
        Route::get('/my/all', 'allPostsByOwner')->middleware('cookie.token');
        Route::get('/{slug}', 'show');
        // Route::post('/{slug}/destroy', 'destroy');
        // Route::post('/{slug}/update', 'update');
        // Route::post('/{slug}/chapter/reset', 'removeFromChapter');
    });

Route::prefix('categories')
    ->controller(CategoryController::class)
    ->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store')->middleware('cookie.token');
        Route::get('/{id}', 'show');
        Route::post('/{id}/update', 'update')->middleware('cookie.token');
        Route::post('/{id}/destroy', 'destroy')->middleware('cookie.token');
    });
    
Route::prefix('tags')
    ->controller(TagController::class)
    ->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store')->middleware('cookie.token');
        Route::get('/{id}', 'show');
        Route::post('/{id}/update', 'update')->middleware('cookie.token');
        Route::post('/{id}/destroy', 'destroy')->middleware('cookie.token');
    });

Route::prefix('comments')
    ->controller(CommentController::class)
    ->group(function () {
        Route::post('/{id}/pin', 'pinComment')->middleware('cookie.token');
        Route::post('/{id}/destroy', 'destroy')->middleware('cookie.token');
        Route::get('/{kind}/{parent_id}', 'index');
        Route::post('/{kind}/{parent_id}', 'store')->middleware('cookie.token');
        Route::post('/{kind}/{parent_id}/{id}', 'update')->middleware('cookie.token');
    });

Route::prefix('series')
    ->controller(ChapterController::class)
    ->middleware('cookie.token')
    ->group(function () {
        Route::get('/', 'index');
        Route::get('/my', 'chaptersByOwner')->middleware('cookie.token');
        Route::get('/{slug}', 'show');
        // Route::post('/{kind}', 'store')->middleware('cookie.token');
        // Route::post('/{kind}/{slug}/update', 'update')->middleware('cookie.token');
        // Route::post('/{kind}/{slug}/destroy', 'destroy')->middleware('cookie.token');
    });

Route::prefix('favorites')
    ->controller(FavoriteController::class)
    ->group(function () {
        Route::get('/', 'index')->middleware('cookie.token');
        Route::get('/{kind}/{parent_id}', 'favoritesByParent');
        Route::post('/{kind}/{parent_id}', 'toggleFavorite')->middleware('cookie.token');
    });

Route::prefix('likes')
    ->controller(LikeController::class)
    ->group(function () {
        Route::get('/{kind}/{parent_id}', 'index');
        Route::post('/{kind}/{parent_id}', 'toggleLike')->middleware('cookie.token');
    });

Route::prefix('shorteners')
    ->controller(ShortenerController::class)
    ->group(function () {
        Route::get('/', 'index');
    });
