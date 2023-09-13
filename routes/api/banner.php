<?php

use App\Http\Controllers\BannerController;
use Illuminate\Support\Facades\Route;

Route::get('/{banner}', [BannerController::class, 'show']);

Route::group(['middleware' => ['auth:api', 'admin']], function () {
    Route::get('/', [BannerController::class, 'index']);
    Route::post('/', [BannerController::class, 'store']);
    Route::match(['put', 'post'], '/{banner}', [BannerController::class, 'update']);
    Route::delete('/{banner}', [BannerController::class, 'destroy']);
});
