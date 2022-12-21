<?php

use App\Http\Controllers\AnimeController;
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
    Route::get('/user/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/user/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/user/delete', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/user/friends', [ProfileController::class, 'friends'])->name('profile.friends');
    Route::get('/user/favorites', [ProfileController::class, 'favorites'])->name('profile.favorites');
    Route::get('/user/ratings', [ProfileController::class, 'ratings'])->name('profile.ratings');
    Route::get('/user/to-watch', [ProfileController::class, 'to_watch'])->name('profile.to-watch');
});

Route::get('/anime', [AnimeController::class, 'index'])->name('animes.index');
Route::get('/anime/create', [AnimeController::class, 'create'])->name('animes.create');
Route::post('/anime', [AnimeController::class, 'store'])->name('animes.store');
Route::get('/anime/{title}-{production_year}-{id}', [AnimeController::class, 'show'])->name('animes.show');
Route::get('/anime/{anime}/edit/', [AnimeController::class, 'edit'])->name('animes.edit');
Route::get('/anime/{anime}/delete', [AnimeController::class, 'destroy'])->name('animes.delete');
Route::patch('/anime/update', [AnimeController::class, 'update'])->name('animes.update');

require __DIR__.'/auth.php';

Route::resource('comments', \App\Http\Controllers\CommentController::class);
