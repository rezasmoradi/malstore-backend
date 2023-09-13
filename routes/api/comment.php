<?php

use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:api']], function (){
    Route::post('/{product}', [CommentController::class, 'store']);
    Route::delete('/{comment}', [CommentController::class, 'destroy']);
});

