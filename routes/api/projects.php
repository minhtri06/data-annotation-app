<?php

use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ProjectController::class, 'index']);
Route::post('/', [ProjectController::class, 'store']);
Route::middleware('auth:sanctum')->get('/{id}', [ProjectController::class, 'show']);
Route::middleware('auth:sanctum')->delete('/{id}', [ProjectController::class, 'destroy']);
