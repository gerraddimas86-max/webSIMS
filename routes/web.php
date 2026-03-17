<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\post\PostController;
use App\Http\Controllers\post\CommentController;
use App\Http\Controllers\post\LikeController;
use App\Http\Controllers\user\ConnectionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\user\ProfileController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminEventController;
use App\Http\Controllers\Admin\AdminUserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// =====================
// ROOT
// =====================
Route::get('/', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }
    return auth()->user()->role === 'admin'
        ? redirect()->route('admin.dashboard')
        : redirect()->route('posts.index');
});


// =====================
// AUTH ROUTES
// =====================
Route::get('/login', [AuthController::class, 'showLogin'])
    ->middleware('guest')
    ->name('login');

Route::post('/login', [AuthController::class, 'login'])
    ->middleware('guest');

Route::get('/register', [AuthController::class, 'showRegister'])
    ->middleware('guest')
    ->name('register');

Route::post('/register', [AuthController::class, 'register'])
    ->middleware('guest');

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');


// =====================
// ADMIN ROUTES
// =====================
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [AdminDashboardController::class, 'index'])
        ->name('dashboard');

    Route::resource('events', AdminEventController::class);

    Route::get('/events/{event}/participants', [AdminEventController::class, 'participants'])
        ->name('events.participants');

    Route::resource('users', AdminUserController::class);

});


// =====================
// USER ROUTES
// =====================
Route::middleware('auth')->group(function () {

    // INTRO SCREEN
    Route::get('/intro', function () {
        return view('intro');
    })->name('intro');

    // POSTS
    Route::resource('posts', PostController::class);

    // COMMENTS
    Route::post('/posts/{post}/comment', [CommentController::class, 'store'])
        ->name('comments.store');

    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])
        ->name('comments.destroy');

    // LIKES
    Route::post('/posts/{post}/like', [LikeController::class, 'toggle'])
        ->name('posts.like');

    // CONNECTIONS
    Route::get('/connections', [ConnectionController::class, 'index'])
        ->name('connections.index');

    Route::post('/connect/{id}', [ConnectionController::class, 'sendRequest'])
        ->name('connect.send');

    Route::post('/connect/{id}/accept', [ConnectionController::class, 'accept'])
        ->name('connect.accept');

    Route::delete('/connect/{id}', [ConnectionController::class, 'remove'])
        ->name('connect.remove');

    // PROFILE
    Route::get('/profile', [ProfileController::class, 'index'])
        ->name('profile.index');

});