<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


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


Auth::routes(['verify' => true]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/place/{id}', [App\Http\Controllers\HomeController::class, 'indexPlace'])->name('home');

Route::middleware(['admin'])->group(function () {
    Route::get('/admin', [App\Http\Controllers\AdminController::class, 'index'])->name('admin');
    Route::get('/admin/places', [App\Http\Controllers\AdminController::class, 'places'])->name('admin.places');
    Route::get('/admin/users', [App\Http\Controllers\AdminController::class, 'users'])->name('admin.users');
    Route::get('/admin/comments', [App\Http\Controllers\AdminController::class, 'comments'])->name('admin.comments');
    Route::get('/admin/categories', [App\Http\Controllers\AdminController::class, 'categories'])->name('admin.categories');
    Route::get('/admin/logs', [App\Http\Controllers\AdminController::class, 'logs'])->name('admin.logs');
    Route::get('/admin/orderplaces', [App\Http\Controllers\AdminController::class, 'orderPlaces']);
    Route::get('/admin/orderusers', [App\Http\Controllers\AdminController::class, 'orderUsers']);
    Route::get('/admin/ordercomments', [App\Http\Controllers\AdminController::class, 'orderComments']);
    Route::get('/admin/ordercategories', [App\Http\Controllers\AdminController::class, 'orderCategories']);
    Route::get('/admin/orderlogs', [App\Http\Controllers\AdminController::class, 'orderLogs']);
    Route::put('admin/user/{id}', [App\Http\Controllers\AdminController::class, 'updateUsers']);
    Route::delete('admin/user/{id}', [App\Http\Controllers\AdminController::class, 'deleteUser']);
    Route::put('admin/category/{id}', [App\Http\Controllers\AdminController::class, 'updateCategories']);
    Route::delete('admin/category/{id}', [App\Http\Controllers\AdminController::class, 'deleteCategory']);
    Route::put('admin/comment/{id}', [App\Http\Controllers\AdminController::class, 'updateComments']);
    Route::delete('admin/comment/{id}', [App\Http\Controllers\AdminController::class, 'deleteComment']);
    Route::put('admin/place/{id}', [App\Http\Controllers\AdminController::class, 'updatePlaces']);
    Route::delete('admin/place/{id}', [App\Http\Controllers\AdminController::class, 'deletePlace']);
});





