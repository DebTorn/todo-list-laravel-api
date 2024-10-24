<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

//region Auth
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {

    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
});
//endregion

//region Category
Route::get('/category/{id?}', [CategoryController::class, 'index']);

Route::group([
    'middleware' => 'api',
    'prefix' => 'category'
], function ($router) {

    Route::put('/', [CategoryController::class, 'store']);
    Route::delete('/', [CategoryController::class, 'destroy']);
});

//endregion
