<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/main', function () {
    return view('main');
})->name('main');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

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
