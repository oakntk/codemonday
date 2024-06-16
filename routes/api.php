<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;

Route::post('/categories', [CategoryController::class, 'storeStandalone']);
Route::post('/categories/{parentId}', [CategoryController::class, 'storeLeaf']);
Route::get('/categories/{id}', [CategoryController::class, 'show']);
Route::get('/categories/{id}/tree', [CategoryController::class, 'showTree']);
Route::get('/categories', [CategoryController::class, 'index']);
Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);
