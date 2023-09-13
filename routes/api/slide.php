<?php

use App\Http\Controllers\SlideController;
use Illuminate\Support\Facades\Route;

Route::get('/', [SlideController::class, 'show']);

Route::group(['middleware' => ['auth:api', 'admin']], function () {
    Route::get('/', [SlideController::class, 'index']);
    Route::post('/', [SlideController::class, 'store']);
    Route::match(['put', 'post'], '/{slide}', [SlideController::class, 'update']);
    Route::delete('/{slide}', [SlideController::class, 'destroy']);
});
