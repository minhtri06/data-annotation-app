<?php

use App\Http\Controllers\ProjectTypeController as Controller;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')
    ->get('/', [Controller::class, 'index']);

Route::middleware(['auth:sanctum', 'ability:manager,admin'])
    ->post('/', [Controller::class, 'store']);
    
Route::middleware('auth:sanctum')->get('/{id}', [Controller::class, 'show']);

Route::middleware(['auth:sanctum', 'ability:manager,admin'])
    ->patch('/{id}', [Controller::class, 'update']);

Route::middleware(['auth:sanctum', 'ability:manager,admin'])
    ->delete('/{id}', [Controller::class, 'destroy']);