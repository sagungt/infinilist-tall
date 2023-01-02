<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChapterController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ShortenerController;
use App\Http\Controllers\UserController;
use App\Http\Livewire\Errors\NotFound;
use App\Http\Livewire\Favorites\Index as FavoriteIndex;
use App\Http\Livewire\Likes\Index as LikeIndex;
use App\Http\Livewire\Page\NewPost;
use App\Http\Livewire\Page\Post;
use App\Http\Livewire\Posts\My as MyPost;
use App\Http\Livewire\Profile\Edit;
use App\Http\Livewire\Profile\Show;
use App\Http\Livewire\Series\My as MySeries;
use App\Http\Livewire\Series\NewSeries;
use App\Http\Livewire\Series\Show as SeriesShow;
use App\Http\Livewire\Shorteners\Add as ShortenerAdd;
use App\Http\Livewire\Shorteners\Index as ShortenerIndex;
use App\Http\Livewire\Comments\Index as CommentIndex;
use App\Http\Livewire\Posts\Edit as PostsEdit;
use App\Http\Livewire\Posts\Index as PostIndex;
use App\Http\Livewire\Series\Edit as SeriesEdit;
use App\Http\Livewire\Series\Index as SeriesIndex;
use App\Http\Livewire\Shorteners\Edit as ShortenersEdit;
use Illuminate\Support\Facades\Route;
use Laravel\Ui\AuthCommand;

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

Route::view('/', 'welcome')->name('home');
Route::prefix('auth')
    ->controller(AuthController::class)
    ->group(function () {
        Route::get('/login', 'loginView')
            ->name('login');
        Route::post('/login', 'login')
            ->name('process.login');
        Route::get('/register', 'registerView')
            ->name('register');
        Route::post('/register', 'register')
            ->name('process.register');
        Route::post('/logout', 'logout')
            ->name('logout');
    });

Route::get('/user/{username}', [UserController::class, 'profile'])->name('user.profile');

Route::prefix('me')
    ->middleware('auth:sanctum')
    ->name('profile.')
    ->group(function () {
        Route::get('/', Show::class)
            ->name('show');
        Route::post('/', [UserController::class, 'update'])
            ->name('update');
        Route::get('/edit', Edit::class)
            ->name('edit');
        Route::get('/posts', MyPost::class)
            ->name('post.list');
        Route::get('/series', MySeries::class)
            ->name('series.list');
        Route::get('/favorites', FavoriteIndex::class)
            ->name('favorite.list');
        Route::get('/likes', LikeIndex::class)
            ->name('like.list');
        Route::get('/comments', CommentIndex::class)
            ->name('comment.list');
    });

Route::prefix('shorteners')
    ->name('shortener.')
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::get('/', ShortenerIndex::class)
            ->name('list');
        Route::get('/add', ShortenerAdd::class)
            ->name('add');
        Route::post('/', [ShortenerController::class, 'store'])
            ->name('store');
        Route::get('/{id}/edit', ShortenersEdit::class)
            ->name('edit');
        Route::post('/{id}/update', [ShortenerController::class, 'update'])
            ->name('update');
        Route::post('/{id}/destroy', [ShortenerController::class, 'destroy'])
            ->name('destroy');
    });

Route::prefix('s')
    ->controller(ShortenerController::class)
    ->group(function () {
        Route::get('/{url}', 'redirect')
            ->name('shortener');
    });

Route::prefix('posts')
    ->name('post.')
    ->group(function () {
        Route::get('/', PostIndex::class)
            ->name('list');
        Route::post('/', [PostController::class, 'store'])
            ->name('store')
            ->middleware('auth:sanctum');
        Route::get('/add', NewPost::class)->name('add')
            ->middleware('auth:sanctum');
        Route::get('/{slug}', Post::class)
            ->name('show');
        Route::get('/{slug}/edit', PostsEdit::class)
            ->name('edit')
            ->middleware('auth:sanctum');
        Route::post('/{slug}/update', [PostController::class, 'update'])
            ->name('update')
            ->middleware('auth:sanctum');
        Route::post('/{slug}/destroy', [PostController::class, 'destroy'])
            ->name('destroy')
            ->middleware('auth:sanctum');
        Route::post('/upload', [PostController::class, 'upload'])
            ->name('ckeditor.upload')
            ->middleware('auth:sanctum');
    });

Route::prefix('series')
    ->name('series.')
    ->group(function () {
        Route::get('/', SeriesIndex::class)
            ->name('list');
        Route::get('/add', NewSeries::class)
            ->name('add')
            ->middleware('auth:sanctum');
        Route::post('/{kind}', [ChapterController::class, 'store'])
            ->name('store')
            ->middleware('auth:sanctum');
        Route::get('/{slug}', SeriesShow::class)
            ->name('show');
        Route::get('/{kind}/{slug}/edit', SeriesEdit::class)
            ->name('edit')
            ->middleware('auth:sanctum');
        Route::post('/{kind}/{slug}/update', [ChapterController::class, 'update'])
            ->name('update')
            ->middleware('auth:sanctum');
        Route::post('/{kind}/{slug}/destroy', [ChapterController::class, 'destroy'])
            ->name('destroy')
            ->middleware('auth:sanctum');
    });

Route::prefix('favorites')
    ->name('favorite.')
    ->controller(FavoriteController::class)
    ->group(function () {
        Route::post('/{kind}/{parent_id}', 'toggleFavorite')
            ->name('toggle')
            ->middleware('auth:sanctum');
    });

Route::prefix('likes')
    ->name('like.')
    ->controller(LikeController::class)
    ->group(function () {
        Route::post('/{kind}/{parent_id}', 'toggleLike')
            ->name('toggle')
            ->middleware('auth:sanctum');
    });

Route::prefix('comments')
    ->name('comment.')
    ->controller(CommentController::class)
    ->group(function () {
        Route::post('/{kind}/{parent_id}', 'store')
            ->name('store')
            ->middleware('auth:sanctum');
    });

Route::prefix('error')
    ->name('error.')
    ->group(function () {
        Route::get('/404', NotFound::class)
            ->name('not-found');
    });
