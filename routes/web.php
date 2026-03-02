<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\ConnectionController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// =====================
// ROOT
// =====================

// Kalau belum login → ke login
// Kalau sudah login → ke posts
Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('posts.index')
        : redirect()->route('login');
});


// =====================
// AUTH ROUTES
// =====================

// Login
Route::get('/login', [AuthController::class, 'showLogin'])
    ->middleware('guest')
    ->name('login');

Route::post('/login', [AuthController::class, 'login'])
    ->middleware('guest');

// Register
Route::get('/register', [AuthController::class, 'showRegister'])
    ->middleware('guest')
    ->name('register');

Route::post('/register', [AuthController::class, 'register'])
    ->middleware('guest');

// Logout
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');


// =====================
// ROUTE YANG BUTUH LOGIN
// =====================
Route::middleware('auth')->group(function () {

    // CRUD Post
    Route::resource('posts', PostController::class);

    // Comment
    Route::post('/posts/{post}/comment',
        [CommentController::class, 'store'])
        ->name('comments.store');

    Route::delete('/comments/{comment}',
        [CommentController::class, 'destroy'])
        ->name('comments.destroy');

    // Like
    Route::post('/posts/{post}/like',
        [LikeController::class, 'toggle'])
        ->name('posts.like');

    // Connection
    Route::post('/connect/{id}',
        [ConnectionController::class, 'sendRequest'])
        ->name('connect.send');

    Route::post('/connect/{id}/accept',
        [ConnectionController::class, 'accept'])
        ->name('connect.accept');

    Route::delete('/connect/{id}',
        [ConnectionController::class, 'remove'])
        ->name('connect.remove');
});