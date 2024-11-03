<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ListController;
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
Route::group([
    'middleware' => 'api',
    'prefix' => 'category'
], function ($router) {
    Route::get('/category/{id?}', [CategoryController::class, 'index']);
    Route::post('/', [CategoryController::class, 'store']);
    Route::delete('/', [CategoryController::class, 'destroy']);
});

//endregion

//region List
Route::group([
    'middleware' => 'api',
    'prefix' => 'lists'
], function ($router) {
    Route::get('/{id?}', [ListController::class, 'index']);
    Route::post('/', [ListController::class, 'store']);
    Route::delete('/{id}', [ListController::class, 'destroy']);
});
//endregion

//region Item
Route::group([
    'middleware' => 'api',
    'prefix' => 'items'
], function ($router) {
    Route::get('/{listId?}/{itemId?}', [ItemController::class, 'index']);
    Route::post('/', [ItemController::class, 'store']);
    Route::patch('/{id}', [ItemController::class, 'update']);
    Route::delete('/', [ItemController::class, 'destroy']);
});
//endregion
