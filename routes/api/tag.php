<?php

use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;

Route::get('/{search}', [TagController::class, 'show'])->middleware(['auth:api', 'admin']);
