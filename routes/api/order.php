<?php

use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::post('/{product}', [OrderController::class, 'store']);
Route::put('/{order_detail}', [OrderController::class, 'update']);
Route::delete('/{order_detail}', [OrderController::class, 'delete']);
Route::delete('/', [OrderController::class, 'destroy']);
Route::get('/', [OrderController::class, 'index']);

Route::group(['middleware' => ['auth:api']], function () {
    Route::post('/', [OrderController::class, 'create']);
});

Route::group(['middleware' => ['auth:api', 'admin']], function () {
    Route::post('/shopper/{product}', [OrderController::class, 'register']);
});
