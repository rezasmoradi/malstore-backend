<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/verify', [AuthController::class, 'verify']);
Route::post('/resend', [AuthController::class, 'resend']);
Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->middleware(['auth:api']);

/*

Client ID: 1
Client secret: fjLnARMVcE0SqsgRMKAQ4DcutWoo6UWGOsQaUXTl
Password grant client created successfully.
Client ID: 2
Client secret: v3Qgp91nTi0qDbuOx05ZLsCREbeVCAFeldO5xnNX

*/
