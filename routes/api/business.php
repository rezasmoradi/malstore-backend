<?php

use App\Http\Controllers\BusinessController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:api', 'admin']], function () {
    Route::get('/', [BusinessController::class, 'index']);
    Route::post('/', [BusinessController::class, 'store']);
    Route::delete('/{business}', [BusinessController::class, 'destroy']);
});
