<?php

use Illuminate\Support\Facades\Route;

Route::apiResource('', \App\Http\Controllers\ShopperController::class)
    ->middleware(['auth:api', 'admin'])
    ->parameter('', 'shopper');
