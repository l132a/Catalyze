<?php

use Illuminate\Http\Request;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//url/api/v1/
Route::group(['prefix' => 'v1', 'namespace' => 'api'], function () {
    Route::get('/all-post', [App\Http\Controllers\PostController::class, 'all'])->name('api.posts.all');
    Route::get('/get-post/{id}', [App\Http\Controllers\PostController::class, 'get'])->name('api.posts.get');
});
