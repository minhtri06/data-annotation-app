<?php

use App\Http\Controllers\ProjectTypeController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')
    ->get('/', [ProjectTypeController::class, 'index']);
