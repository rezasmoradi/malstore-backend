<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:api']], function () {
    Route::get('/me', [UserController::class, 'me']);
    Route::put('/', [UserController::class, 'update']);
    Route::post('/', [UserController::class, 'updateAvatar']);
});

Route::group(['middleware' => ['auth:api', 'admin']], function () {
    Route::get('/', [UserController::class, 'index']);
    Route::get('/{user}', [UserController::class, 'show']);
    Route::post('/{user}/promote', [UserController::class, 'promote']);
});
