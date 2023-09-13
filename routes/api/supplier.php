<?php

use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

Route::apiResource('', SupplierController::class)
    ->middleware(['auth:api', 'admin'])
    ->parameter('', 'supplier');

Route::group(['middleware' => ['auth:api', 'admin']], function () {
    Route::post('/{supplier}/restore', [SupplierController::class, 'restore']);
    Route::delete('/{supplier}/permanent', [SupplierController::class, 'delete']);
});
