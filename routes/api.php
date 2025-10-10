<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProfileController;

// маршруты (без токена)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

// защищённые маршруты
Route::middleware('auth:sanctum')->group(function () {
    // Аутентификация
    Route::post('/logout',   [AuthController::class, 'logout']);
    Route::get('/user',      [AuthController::class, 'user']);

    // Профиль
    Route::get('/profile',    [ProfileController::class, 'show']);
    Route::put('/profile',    [ProfileController::class, 'update']);
    Route::delete('/profile', [ProfileController::class, 'destroy']);
});