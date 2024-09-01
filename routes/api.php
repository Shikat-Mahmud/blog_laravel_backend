<?php

use App\Http\Controllers\BlogController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('blogs', [BlogController::class, 'index']);
Route::post('blog', [BlogController::class, 'store']);
Route::get('blog/{id}', [BlogController::class, 'show']);
Route::put('blog/{id}', [BlogController::class, 'update']);