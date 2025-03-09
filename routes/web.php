<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MainController;
use Illuminate\Support\Facades\Route;

// Auth Autentication
Route::get('/login', [AuthController::class, 'login']);
Route::post('/login', [AuthController::class, 'loginSubmit'])->name('auth.loginSubmit');
Route::post('/logout', [AuthController::class, 'logout']);
