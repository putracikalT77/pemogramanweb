<?php

use App\Http\Controllers\api\UserController;
use App\Http\Controllers\Auth\AdminAuthController;
use Illuminate\Support\Facades\Route;


Route::post('/login', [AdminAuthController::class, 'loginApi']);
Route::post('/register', [AdminAuthController::class, 'register']);
Route::get('/users-list', [UserController::class, 'getData'])->name('list-users');

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('user', UserController::class);
    Route::post('/logout', [AdminAuthController::class, 'logoutApi']);
});