<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', function () {
    return view('login');
});

Route::middleware('auth')->group(function () {

    Route::resource('posts', PostController::class);

    Route::post('/posts/{post}/comment', [CommentController::class, 'store']);
    Route::delete('/comments/{id}', [CommentController::class, 'destroy']);

    Route::post('/posts/{post}/like', [LikeController::class, 'toggle']);

    Route::post('/connect/{id}', [ConnectionController::class, 'sendRequest']);
    Route::post('/connect/{id}/accept', [ConnectionController::class, 'accept']);
    Route::delete('/connect/{id}', [ConnectionController::class, 'remove']);

});