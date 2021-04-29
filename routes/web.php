<?php

use Illuminate\Support\Facades\Auth;
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
    return view('frontend');
});

Auth::routes(['register' => false, 'reset' => false, 'verify' => false]);

Route::group(['middleware' => 'auth', 'check.user'], function () {
    Route::group(['prefix' => 'admin'], function () {
        Route::get('/', [App\Http\Controllers\AdminController::class, 'index'])->name('admin');
        Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'index'])->name('admin.profile');
        Route::group(['prefix' => 'users'], function () {
            Route::get('/', [App\Http\Controllers\UserController::class, 'index'])->name('admin.users');
            Route::post('/store', [App\Http\Controllers\UserController::class, 'store'])->name('admin.users.store');
            Route::get('/edit', [App\Http\Controllers\UserController::class, 'edit'])->name('admin.users.edit');
            Route::post('/destroy', [App\Http\Controllers\UserController::class, 'destroy'])->name('admin.users.destroy');
        });
        Route::group(['prefix' => 'categories'], function () {
            Route::get('/', [App\Http\Controllers\CategoryController::class, 'index'])->name('admin.categories');
            Route::post('/store', [App\Http\Controllers\CategoryController::class, 'store'])->name('admin.categories.store');
            Route::get('/edit', [App\Http\Controllers\CategoryController::class, 'edit'])->name('admin.categories.edit');
            Route::get('/show', [App\Http\Controllers\CategoryController::class, 'show'])->name('admin.categories.show');
            Route::post('/destroy', [App\Http\Controllers\CategoryController::class, 'destroy'])->name('admin.categories.destroy');
        });
        Route::group(['prefix' => 'posts'], function () {
            Route::get('/', [App\Http\Controllers\PostController::class, 'index'])->name('admin.posts');
            Route::post('/store', [App\Http\Controllers\PostController::class, 'store'])->name('admin.posts.store');
            Route::get('/edit', [App\Http\Controllers\PostController::class, 'edit'])->name('admin.posts.edit');
            Route::post('/destroy', [App\Http\Controllers\PostController::class, 'destroy'])->name('admin.posts.destroy');
        });
    });
});
