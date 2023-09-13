<?php

use App\Http\Controllers\ShipperController;
use Illuminate\Support\Facades\Route;

Route::apiResource('', ShipperController::class)
    ->middleware(['auth:api', 'admin'])
    ->parameter('', 'shipper');

Route::group(['middleware' => ['auth:api', 'admin']], function () {
    Route::post('/{shipper_id}/restore', [ShipperController::class, 'restore']);
    Route::delete('/{shipper_id}/permanent', [ShipperController::class, 'delete']);
});
