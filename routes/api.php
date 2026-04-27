<?php

use Illuminate\Http\Request;
use App\Http\Controllers\AIController;
use App\Http\Controllers\ImageAIController;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/chat', AIController::class);
Route::post('/vision', ImageAIController::class);
