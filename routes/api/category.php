<?php


use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

Route::apiResource('', CategoryController::class)
    ->middleware(['auth:api', 'admin'])
    ->parameter('', 'category');
