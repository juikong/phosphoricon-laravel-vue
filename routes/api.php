<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\IconController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('phosphor-icon', [IconController::class, 'index']);
Route::get('categories', [CategoryController::class, 'index']);
Route::post('category-edit', [CategoryController::class, 'update']);
