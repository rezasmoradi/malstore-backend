<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/category/{category_id}', [ProductController::class, 'index']);
Route::get('/{product}', [ProductController::class, 'show']);

Route::group(['middleware' => ['auth:api']], function () {
    Route::post('/{product}/rating', [ProductController::class, 'rating']);
});


Route::group(['middleware' => ['auth:api', 'admin']], function () {
    Route::post('/', [ProductController::class, 'store']);
    Route::match(['put', 'post'], '/{product}', [ProductController::class, 'update']);
    Route::delete('/{product}', [ProductController::class, 'delete']);
    Route::post('/{slug}/restore', [ProductController::class, 'restore']);
    Route::delete('/{slug}/permanent', [ProductController::class, 'destroy']);
    Route::delete('/{product}/{image}', [ProductController::class, 'deletePhoto']);
});
