<?php

use App\Http\Controllers\AnimeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\AnimeUsersController;
use App\Http\Controllers\ProfileController;
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
    Route::get('/user/{username}/favorites', [ProfileController::class, 'favorites'])->name('profile.favorites');
    Route::get('/user/{username}/ratings', [ProfileController::class, 'ratings'])->name('profile.ratings');
    Route::get('/user/{username}/to-watch', [ProfileController::class, 'to_watch'])->name('profile.to-watch');
    Route::post('/user/{username}/upload-image', [ProfileController::class, 'store_image'])->name('image.store');
    Route::get('/user/{username}/add-to-friends', [ProfileController::class, 'add_to_friends'])->name('user.invite');
});

Route::get('/anime', [AnimeController::class, 'index'])->name('animes.index');
Route::get('/anime/create', [AnimeController::class, 'create'])->middleware(['auth', 'verified'])->name('animes.create');
Route::post('/anime', [AnimeController::class, 'store'])->middleware(['auth', 'verified'])->name('animes.store');
Route::get('/anime/{title}-{production_year}-{id}', [AnimeController::class, 'show'])->name('animes.show');
Route::get('/anime/{anime}/edit/', [AnimeController::class, 'edit'])->middleware(['auth', 'verified'])->name('animes.edit');
Route::get('/anime/{anime}/delete', [AnimeController::class, 'destroy'])->middleware(['auth', 'verified'])->name('animes.delete');
Route::patch('/anime/update', [AnimeController::class, 'update'])->middleware(['auth', 'verified'])->name('animes.update');
Route::get('/anime/filter', [AnimeController::class, 'filter'])->name('animes.filter');

Route::get('/anime/favorites', [AnimeUsersController::class, 'favorite'])->middleware(['auth', 'verified'])->name('animes_users.favorite');
Route::get('/anime/to_watch', [AnimeUsersController::class, 'to_watch'])->middleware(['auth', 'verified'])->name('animes_users.to_watch');
Route::get('/anime/rate', [AnimeUsersController::class, 'rate'])->middleware(['auth', 'verified'])->name('animes_users.rate');

Route::get('/anime/{anime}/comments', [CommentController::class, 'show'])->name('comments.show');
Route::post('/comments', [CommentController::class, 'store'])->middleware(['auth', 'verified'])->name('comments.store');
Route::get('/commentsupdate', [CommentController::class, 'update'])->middleware(['auth', 'verified'])->name("comments.update");
Route::get('/commentsdelete', [CommentController::class, 'destroy'])->middleware(['auth', 'verified'])->name('comments.delete');
Route::get('/commentsdislike', [CommentController::class, 'dislike'])->middleware(['auth', 'verified'])->name('comments.dislike');
Route::get('/commentslike', [CommentController::class, 'like'])->middleware(['auth', 'verified'])->name('comments.like');

require __DIR__.'/auth.php';

//Route::resource('comments', \App\Http\Controllers\CommentController::class);
