<?php

use App\Http\Controllers\MeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MeController::class, 'getMyProfile']);
