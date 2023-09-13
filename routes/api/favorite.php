<?php

use App\Http\Controllers\FavoriteController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:api']], function () {
    Route::get('/', [FavoriteController::class, 'index']);
    Route::post('/{product_id}', [FavoriteController::class, 'store']);
    Route::delete('/{product_id}', [FavoriteController::class, 'destroy']);
});
