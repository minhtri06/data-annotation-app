<?php

use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/', [ProjectController::class, 'index']);

Route::middleware(['auth:sanctum', 'ability:manager,admin'])
    ->post('/', [ProjectController::class, 'store']);

Route::middleware('auth:sanctum')->get('/{id}', [ProjectController::class, 'show']);

Route::middleware(['auth:sanctum', 'ability:manager,admin'])
    ->patch('/{id}', [ProjectController::class, 'update']);

Route::middleware(['auth:sanctum', 'ability:manager,admin'])
    ->delete('/{id}', [ProjectController::class, 'destroy']);

Route::middleware(['auth:sanctum', 'ability:manager,admin'])
    ->post('/{id}/assignment', [ProjectController::class, 'assignUsersToProject']);


Route::middleware(['auth:sanctum', 'ability:manager,admin'])
    ->get(
        '/{id}/un-assignment',
        [ProjectController::class, 'getUnassignedUsers']
    );

Route::middleware(['auth:sanctum', 'ability:manager,admin'])
    ->post('/{id}/files', [ProjectController::class, 'importFile']);
