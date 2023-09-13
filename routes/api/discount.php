<?php

use App\Http\Controllers\DiscountController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:api', 'admin']], function () {
    Route::apiResource('', DiscountController::class)->parameter('', 'discount');
    Route::delete('/{discount_id}/permanent', [DiscountController::class, 'delete']);
    Route::post('/{discount_id}/restore', [DiscountController::class, 'restore']);
});
