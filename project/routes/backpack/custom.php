<?php

use App\Http\Controllers\Admin\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::get('admin/register', [RegisterController::class, 'showRegistrationForm'])->name('backpack.auth.register');
Route::post('admin/register', [RegisterController::class, 'register'])->name('backpack.auth.register');


Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    Route::crud('anime', 'AnimeCrudController');
    Route::crud('anime-users', 'AnimeUsersCrudController');
    Route::crud('comment', 'CommentCrudController');
    Route::crud('review', 'ReviewCrudController');
    Route::crud('review-users', 'ReviewUsersCrudController');
    Route::crud('user', 'UserCrudController');
    Route::crud('users-friends', 'UsersFriendsCrudController');
    Route::crud('article', 'ArticleCrudController');
}); // this should be the absolute last line of this file