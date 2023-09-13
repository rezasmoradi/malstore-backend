<?php


use App\Http\Controllers\AddressController;
use Illuminate\Support\Facades\Route;

Route::apiResource('', AddressController::class)
    ->middleware(['auth:api'])
    ->parameter('', 'address');

