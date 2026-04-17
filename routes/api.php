<?php
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\StadiumController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/verify-2fa', [AuthController::class, 'verifyTwoFactor']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});
Route::get('/stadiums', [StadiumController::class, 'index']);
Route::get('/stadiums/search', [StadiumController::class, 'search']);
Route::get('/stadiums/{stadium}', [StadiumController::class, 'show']);
    Route::middleware('auth:sanctum')->group(function () {
    Route::get('/users/me', [UserController::class, 'me']);
    Route::get('/users/me/favorites', [FavoriteController::class, 'index']);
    Route::post('/users/me/favorites', [FavoriteController::class, 'store']);
    Route::delete('/users/me/favorites/{stadium}', [FavoriteController::class, 'destroy']);
    Route::get('/users/me/favorites/{stadium}', [FavoriteController::class, 'check']);
});
