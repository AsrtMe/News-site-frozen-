<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CommentApiController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProfileController;


// Группа маршрутов аутентификации с префиксом и именами
Route::prefix('auth')->name('auth.')->group(function () {
    // маршруты (без токена)
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');

    // Гугл аутентификация (используем метод в AuthController)
    Route::post('/google', [AuthController::class, 'googleLogin'])->name('google');

    // защищённые маршруты, требующие Sanctum
    Route::middleware('auth:sanctum')->group(function () {
        // Аутентификация
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/user', [AuthController::class, 'user'])->name('user');

        // Профиль
        Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });
});
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Public API routes (no auth required for GET, auth required for POST/PUT/DELETE)
Route::prefix('comments')->group(function () {
    // Compatibility route: allow GET /api/posts/{postId}/comments
    Route::get('/posts/{postId}/comments', [CommentApiController::class, 'indexByPost']);

    // Get comments for a post
    Route::get('/post/{postId}', [CommentApiController::class, 'indexByPost']);
    
    // Get single comment with replies
    Route::get('/{id}', [CommentApiController::class, 'show']);
    
    // Create new comment or reply — публично доступно (гости могут писать)
    Route::post('/', [CommentApiController::class, 'store']);

    // Authenticated routes (редактирование, удаление, лайки)
    Route::middleware('auth:sanctum')->group(function () {
        // Update comment
        Route::put('/{id}', [CommentApiController::class, 'update']);
        
        // Delete comment
        Route::delete('/{id}', [CommentApiController::class, 'destroy']);
        
        // Toggle like/dislike
        Route::post('/{id}/like', [CommentApiController::class, 'toggleLike']);
    });
});

