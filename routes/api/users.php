<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/', [UserController::class, 'index']);
Route::post('/', [UserController::class, 'store']);
Route::get('/{userId}', [UserController::class, 'show']);
Route::patch('/{userId}', [UserController::class, 'update']);
Route::delete('/{userId}', [UserController::class, 'delete']);
