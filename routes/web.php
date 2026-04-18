<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});



// API routes moved to routes/api.php


// Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
