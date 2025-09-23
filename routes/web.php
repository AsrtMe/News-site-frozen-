<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;

Route::get('/', function () {
    return ("Welcome!");
});

Route::get('/about', [PageController::class, 'about']);

Route::get('/users', [PageController::class, 'users']);