<?php

use App\Http\Controllers\v1\CartController;
use App\Http\Controllers\v1\CheckoutController;
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
        Route::get('/{cart_id}/items', [CartController::class, 'listCarItems']);
        Route::post('/{cart_id}/items', [CartController::class, 'addItemToCart']);
        Route::post('/{cart_id}/items/{cart_item_id}', [CartController::class, 'updateCartItem']);
        Route::delete('/{cart_id}/items/{cart_item_id}', [CartController::class, 'removeCartItem']);
    });

    Route::group(['prefix' => '/checkout'], function () {
        Route::post('/{cart_id}', [CheckoutController::class, 'checkout']);
    });
});
