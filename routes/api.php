<?php

    use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProfileController;

// Группа маршрутов аутентификации с префиксом и именами
Route::prefix('auth')->name('auth.')->group(function () {
    // маршруты (без токена)
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login',    [AuthController::class, 'login'])->name('login');

    // Гугл аутентификация (используем метод в AuthController)
    Route::post('/google',   [AuthController::class, 'googleLogin'])->name('google');

    // защищённые маршруты, требующие Sanctum
    Route::middleware('auth:sanctum')->group(function () {
        // Аутентификация
        Route::post('/logout',   [AuthController::class, 'logout'])->name('logout');
        Route::get('/user',      [AuthController::class, 'user'])->name('user');

        // Профиль
        Route::get('/profile',    [ProfileController::class, 'show'])->name('profile.show');
        Route::put('/profile',    [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });
});