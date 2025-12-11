<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CommentApiController;
use App\Http\Controllers\Api\AuthApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Auth routes (no middleware)
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthApiController::class, 'login']);
    Route::post('/register', [AuthApiController::class, 'register']);
    
    // Protected auth routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthApiController::class, 'logout']);
        Route::get('/me', [AuthApiController::class, 'me']);
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
