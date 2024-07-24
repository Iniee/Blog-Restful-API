<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;

// Route to get the authenticated user
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Group routes with token middleware
Route::middleware('token')->group(function () {


    // Blog routes
    Route::prefix('/blog')->group(function () {
        Route::post('/{user}', [BlogController::class, 'store']);
        Route::get('/', [BlogController::class, 'index']);
        Route::delete('/{id}/{user}', [BlogController::class, 'destroy']);
        Route::get('/{id}', [BlogController::class, 'show']);
        Route::put('/{id}/{user}', [BlogController::class, 'update']);

        // Post routes within a blog
        Route::prefix('/{blog}/posts')->group(function () {
            Route::post('/{user}', [PostController::class, 'store']);
            Route::get('/', [PostController::class, 'index']);
        });
    });

    // Post routes
    Route::prefix('/post')->group(function () {
        Route::delete('/{post}/{user}', [PostController::class, 'destroy']);
        Route::get('/{post}', [PostController::class, 'show']);
        Route::put('/{post}/{user}', [PostController::class, 'update']);

        // Like and comment routes for posts
        Route::post('/{post}/like/{user}', [LikeController::class, 'store']);
        Route::post('/{post}/comment/{user}', [CommentController::class, 'store']);
    });
});