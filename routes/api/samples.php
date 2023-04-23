<?php

use App\Http\Controllers\SampleController as Controller;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/', [Controller::class, 'index']);
Route::middleware(['auth:sanctum', 'ability:manager,admin'])
    ->post('/', [Controller::class, 'store']);
