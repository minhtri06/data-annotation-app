<?php

use App\Http\Controllers\SampleController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'abilities:manager'])
    ->get('/', [SampleController::class, 'index']);
