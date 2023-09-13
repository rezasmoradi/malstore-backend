<?php

use App\Http\Controllers\MessageController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:api']], function () {
    Route::get('/', [MessageController::class, 'index']);
    Route::get('/{message}', [MessageController::class, 'show']);
    Route::post('/', [MessageController::class, 'store']);
});


Route::delete('/{message}', [MessageController::class, 'destroy'])->middleware(['auth:api', 'admin']);
