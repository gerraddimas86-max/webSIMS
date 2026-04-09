<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\post\PostController;
use App\Http\Controllers\post\CommentController;
use App\Http\Controllers\post\LikeController;
use App\Http\Controllers\user\ConnectionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\user\ProfileController;
use App\Http\Controllers\user\NotificationController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminEventController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\event\EventController;

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
        // routes/web.php
        Route::post('/posts/{post}/like', [PostController::class, 'like'])->name('posts.like');

    // COMMENTS
        Route::post('/posts/{post}/comment', [CommentController::class, 'store'])
            ->name('comments.store');

        Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])
            ->name('comments.destroy');
    
         Route::put('/comments/{comment}', [CommentController::class, 'update'])
            ->name('comments.update');    

    // LIKES
        Route::post('/posts/{post}/like', [LikeController::class, 'toggle'])
            ->name('posts.like');

    // PROFILE
        Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
        Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

     // PROFILE USER LAIN
        Route::get('/profile/{id}', [ProfileController::class, 'show'])->name('profile.show');

    // USER EVENTS ROUTES
        Route::prefix('events')->name('events.')->group(function () {
        Route::get('/', [EventController::class, 'index'])->name('index');
        Route::get('/{event}', [EventController::class, 'show'])->name('show');
        Route::get('/{event}/register', [EventController::class, 'registerForm'])->name('register');
        Route::post('/{event}/register', [EventController::class, 'register'])->name('register.store');
    });

    // CONNECTIONS ROUTES (LENGKAP)
        Route::prefix('connections')->name('connections.')->group(function () {
        Route::get('/', [ConnectionController::class, 'index'])->name('index');
        Route::get('/chat/{id}', [ConnectionController::class, 'chat'])->name('chat');
        Route::post('/send/{id}', [ConnectionController::class, 'sendMessage'])->name('send');
        Route::post('/request', [ConnectionController::class, 'sendConnectionRequest'])->name('request');
        Route::post('/send-request/{receiverId}', [ConnectionController::class, 'sendRequest'])->name('sendRequest');
        Route::post('/accept/{id}', [ConnectionController::class, 'accept'])->name('accept');
        Route::post('/accept-request/{id}', [ConnectionController::class, 'acceptRequest'])->name('acceptRequest');
        Route::post('/reject-request/{id}', [ConnectionController::class, 'rejectRequest'])->name('rejectRequest');
        Route::delete('/remove/{id}', [ConnectionController::class, 'remove'])->name('remove');
        Route::delete('/remove-connection/{id}', [ConnectionController::class, 'removeConnection'])->name('removeConnection');
    });

    // MESSAGES (Edit & Delete)
        Route::put('/messages/{id}', [ConnectionController::class, 'updateMessage'])->name('messages.update');
        Route::delete('/messages/{id}', [ConnectionController::class, 'deleteMessage'])->name('messages.delete');

    // NOTIFICATIONS
        Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::put('/{id}/read', [NotificationController::class, 'markAsRead'])->name('mark-read');
        Route::put('/read-all', [NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
        Route::get('/unread-count', [NotificationController::class, 'getUnreadCount'])->name('unread-count');
    });

});