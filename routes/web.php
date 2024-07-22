<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CEPController;

Route::get('/', [CEPController::class, 'index']);
Route::get('/search/local/{ceps}', [CEPController::class, 'search']);

