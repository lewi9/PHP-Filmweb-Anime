<?php

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

require __DIR__.'/auth.php';

Route::resource('comments', \App\Http\Controllers\CommentController::class);
