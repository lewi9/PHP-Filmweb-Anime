<?php

use App\Http\Controllers\AnimeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ImageController;
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
Route::get('/anime/create', [AnimeController::class, 'create'])->name('animes.create');
Route::post('/anime', [AnimeController::class, 'store'])->name('animes.store');
Route::get('/anime/{title}-{production_year}-{id}', [AnimeController::class, 'show'])->name('animes.show');
Route::get('/anime/{anime}/edit/', [AnimeController::class, 'edit'])->name('animes.edit');
Route::get('/anime/{anime}/delete', [AnimeController::class, 'destroy'])->name('animes.delete');
Route::patch('/anime/update', [AnimeController::class, 'update'])->name('animes.update');
Route::get('/anime/filter', [AnimeController::class, 'filter'])->name('animes.filter');

Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
Route::get('/commentsupdate', [CommentController::class, 'update'])->name("comments.update");
Route::get('/commentsdelete', [CommentController::class, 'destroy'])->name('comments.delete');
//Route::get('/commentslike', [CommentController::class, 'like'])->name('comments.like');
Route::get('/commentsdislike', [CommentController::class, 'dislike'])->name('comments.dislike');
Route::get('/commentslike', [CommentController::class, 'like'])->name('comments.like');

require __DIR__.'/auth.php';

//Route::resource('comments', \App\Http\Controllers\CommentController::class);
