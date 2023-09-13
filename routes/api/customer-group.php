<?php

use Illuminate\Support\Facades\Route;

Route::apiResource('', \App\Http\Controllers\CustomerGroupController::class)
->middleware(['auth:api', 'admin'])
->parameter('', 'customer_group');
