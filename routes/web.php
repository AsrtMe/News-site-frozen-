<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/main', function () {
    return view('main');
})->name('main');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Группа для обычных авторизованных пользователей
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [PageController::class, 'profile']);
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Группа для админки
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::post('/users/{user}/block', [AdminController::class, 'block'])->name('admin.users.block');
    Route::post('/users/{user}/unblock', [AdminController::class, 'unblock'])->name('admin.users.unblock');
    Route::delete('/users/{user}', [AdminController::class, 'destroy'])->name('admin.users.destroy');
});

require __DIR__.'/auth.php';

Route::get('/test-register', function () {
    return view('test-register');
});

Route::post('/test-register', [RegisteredUserController::class, 'store']);
