<?php

use App\Http\Controllers\v1\CartController;
use App\Http\Controllers\v1\ProductsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['prefix' => '/v1'], function () {
    Route::group(['prefix' => '/products'], function () {
        Route::get('/', [ProductsController::class, 'listProducts']);
    });

    Route::group(['prefix' => '/carts'], function () {
        Route::get('/', [CartController::class, 'listCarts']);
        Route::get('/{cart_id}', [CartController::class, 'getCart']);
    });
});
