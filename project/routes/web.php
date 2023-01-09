<?php

use App\Http\Controllers\AnimeController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\AnimeUsersController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ReviewUsersController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/user/{username}', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/user/{username}/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/user/{username}/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/user/{username}/delete', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/user/{username}/friends', [ProfileController::class, 'friends'])->name('profile.friends');
    Route::get('/user/{username}/watched-episodes', [ProfileController::class, 'watched_episodes'])->name('profile.watched');
    Route::get('/user/{username}/favorites', [ProfileController::class, 'favorites'])->name('profile.favorites');
    Route::get('/user/{username}/ratings', [ProfileController::class, 'ratings'])->name('profile.ratings');
    Route::get('/user/{username}/to-watch', [ProfileController::class, 'to_watch'])->name('profile.to-watch');
    Route::post('/user/{username}/upload-image', [ProfileController::class, 'store_image'])->name('image.store');
    Route::get('/user/{username}/add-to-friends', [ProfileController::class, 'add_to_friends'])->name('user.invite');
});

Route::middleware(['auth', 'verified'])->group(function () {
    //animes users
    Route::get('/anime/manage_list', [AnimeUsersController::class, 'manage_list'])->name('animes_users.manage_list');
    Route::get('/anime/rate', [AnimeUsersController::class, 'rate'])->name('animes_users.rate');
    Route::get('/anime/watched_episodes', [AnimeUsersController::class, 'watched_episodes'])->name('animes_users.episodes');

    //comments
    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::get('/commentsupdate', [CommentController::class, 'update'])->name("comments.update");
    Route::get('/commentsdelete', [CommentController::class, 'destroy'])->name('comments.delete');
    Route::get('/commentslike', [CommentController::class, 'like'])->name('comments.like');

    //reviews
    Route::get('/anime/{anime_title}-{anime_production_year}-{anime_id}/reviews/create', [ReviewController::class, 'create' ])->name('reviews.create');
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::get('/anime/{anime_title}-{anime_production_year}-{anime_id}/reviews/{review_id}/edit', [ReviewController::class, 'edit' ])->name('reviews.edit');
    Route::patch('/reviews', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/anime/{anime_title}-{anime_production_year}-{anime_id}/reviews/{review_id}', [ReviewController::class, 'destroy'])->name('reviews.delete');

    //reviews users
    Route::get('/reviews/rate', [ReviewUsersController::class, 'rate'])->name('reviews_users.rate');
});
//animes
Route::get('/anime', [AnimeController::class, 'index'])->name('animes.index');
Route::get('/anime/{anime_title}-{anime_production_year}-{anime_id}', [AnimeController::class, 'show'])->name('animes.show');
Route::get('/anime/filter', [AnimeController::class, 'filter'])->name('animes.filter');

//comments
Route::get('/anime/{anime_title}-{anime_production_year}-{anime_id}/comments', [CommentController::class, 'show'])->name('comments.show');
Route::get('/commentsfilter', [CommentController::class, 'filter'])->name('comments.filter');

//reviews
Route::get('/anime/{anime_title}-{anime_production_year}-{anime_id}/reviews', [ReviewController::class, 'index' ])->name('reviews.index');
Route::get('/anime/{anime_title}-{anime_production_year}-{anime_id}/reviews/{review_id}', [ReviewController::class, 'show' ])->name('reviews.show');
Route::get('/reviewsfilter', [ReviewController::class, 'filter'])->name('reviews.filter');

//admin
Route::get('/anime/create', [AnimeController::class, 'create'])->middleware(['auth', 'verified'])->name('animes.create');
Route::post('/anime', [AnimeController::class, 'store'])->middleware(['auth', 'verified'])->name('animes.store');
Route::get('/anime/{anime}/edit/', [AnimeController::class, 'edit'])->middleware(['auth', 'verified'])->name('animes.edit');
Route::get('/anime/{anime}/delete', [AnimeController::class, 'destroy'])->middleware(['auth', 'verified'])->name('animes.delete');
Route::patch('/anime/update', [AnimeController::class, 'update'])->middleware(['auth', 'verified'])->name('animes.update');

Route::get('/ratings', [AnimeController::class, 'get_ratings'])->name('ratings');
Route::post('/ratings/calculate', [AnimeController::class, 'calculate_ratings'])->name('ratings.calculate');

Route::post('/articles/like', [ArticleController::class, 'like'])->name('articles.like');
Route::post('/articles/dislike', [ArticleController::class, 'dislike'])->name('articles.dislike');

require __DIR__.'/auth.php';

//Route::resource('comments', \App\Http\Controllers\CommentController::class);
