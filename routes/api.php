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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/place/{id}', [App\Http\Controllers\PlaceController::class, 'show']);
Route::put('/place/{id}', [App\Http\Controllers\PlaceController::class, 'update']);
Route::post('/place/', [App\Http\Controllers\PlaceController::class, 'store']);
Route::delete('/place/{id}', [App\Http\Controllers\PlaceController::class, 'destroy']);
Route::get('/place/{id}/likes', [App\Http\Controllers\PlaceController::class, 'likes']);

Route::get('/comments/{id}', [App\Http\Controllers\CommentController::class, 'show']);
Route::post('/comments/{id}', [App\Http\Controllers\CommentController::class, 'store']);
Route::delete('/comments/{commentId}', [App\Http\Controllers\CommentController::class, 'destroy']);

Route::get('/likes/{id}', [App\Http\Controllers\LikeController::class, 'show']);
Route::post('/likes/{id}', [App\Http\Controllers\LikeController::class, 'store']);
Route::delete('/likes/{id}', [App\Http\Controllers\LikeController::class, 'destroy']);

Route::get('/search', [App\Http\Controllers\HomeController::class, 'orderSearch']);

Route::get('/image', [App\Http\Controllers\ImageController::class, 'index']);
Route::post('/image/upload', [App\Http\Controllers\ImageController::class, 'store']);
Route::delete('/image/{id}', [App\Http\Controllers\ImageController::class, 'destroy']);