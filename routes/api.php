<?php

use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

//region Category
Route::get('/category/{id?}', [CategoryController::class, 'index']);
Route::put('/category', [CategoryController::class, 'store']);
Route::delete('/category', [CategoryController::class, 'destroy']);
//endregion
