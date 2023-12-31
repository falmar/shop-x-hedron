<?php

namespace App\Domains\Carts;

use App\Domains\Carts\Exceptions\CartItemNotFoundException;
use App\Domains\Carts\Exceptions\CartItemQuantityExceededStockException;
use App\Domains\Carts\Exceptions\CartItemQuantityException;
use App\Domains\Carts\Exceptions\CartNotFoundException;
use App\Domains\Carts\Exceptions\NoSessionIdException;
use App\Domains\Carts\Specs\AddItemInput;
use App\Domains\Carts\Specs\AddItemOutput;
use App\Domains\Carts\Specs\CreateCartInput;
use App\Domains\Carts\Specs\CreateCartOutput;
use App\Domains\Carts\Specs\GetCartInput;
use App\Domains\Carts\Specs\GetCartItemsInput;
use App\Domains\Carts\Specs\GetCartItemsOutput;
use App\Domains\Carts\Specs\GetCartOutput;
use App\Domains\Carts\Specs\ListCartsInput;
use App\Domains\Carts\Specs\ListCartsOutput;
use App\Domains\Carts\Specs\RemoveCartInput;
use App\Domains\Carts\Specs\RemoveCartOutput;
use App\Domains\Carts\Specs\RemoveItemInput;
use App\Domains\Carts\Specs\RemoveItemOutput;
use App\Domains\Carts\Specs\UpdateItemInput;
use App\Domains\Carts\Specs\UpdateItemOutput;
use App\Domains\Products\Exceptions\ProductOutOfStockException;
use App\Libraries\Context\Context;

interface CartServiceInterface
{
    /**
     * List shopping carts
     *
     * @param Context $context
     * @param ListCartsInput $input
     * @return ListCartsOutput
     * @throws NoSessionIdException
     */
    public function listCarts(Context $context, ListCartsInput $input): ListCartsOutput;

    /**
     * Get cart by id
     *
     * @param Context $context
     * @param GetCartInput $input
     * @return GetCartOutput
     * @throws CartNotFoundException
     */
    public function getCart(Context $context, GetCartInput $input): GetCartOutput;

    /**
     * Create a new cart
     *
     * @param Context $context
     * @param CreateCartInput $input
     * @return CreateCartOutput
     */
    public function createCart(Context $context, CreateCartInput $input): CreateCartOutput;

    /**
     * Delete the cart
     *
     * @param Context $context
     * @param RemoveCartInput $input
     * @return RemoveCartOutput
     */
    public function removeCart(Context $context, RemoveCartInput $input): RemoveCartOutput;

    /**
     * List cart items
     *
     * @param Context $context
     * @param GetCartItemsInput $input
     * @return GetCartItemsOutput
     * @throws CartNotFoundException
     */
    public function listCardItems(Context $context, GetCartItemsInput $input): GetCartItemsOutput;

    /**
     * Add items cart
     *
     * @param Context $context
     * @param AddItemInput $input
     * @return AddItemOutput
     * @throws CartItemQuantityExceededStockException|ProductOutOfStockException
     * @throws CartNotFoundException
     */
    public function addCartItem(Context $context, AddItemInput $input): AddItemOutput;

    /**
     * Update a cart items
     *
     * @param Context $context
     * @param UpdateItemInput $input
     * @return UpdateItemOutput
     * @throws CartItemNotFoundException|CartItemQuantityException|CartItemQuantityExceededStockException
     * @throws ProductOutOfStockException
     * @throws CartNotFoundException
     */
    public function updateCartItem(Context $context, UpdateItemInput $input): UpdateItemOutput;

    /**
     * Remove cart items
     *
     * @param Context $context
     * @param RemoveItemInput $input
     * @return RemoveItemOutput
     * @throws CartItemNotFoundException
     * @throws CartNotFoundException
     */
    public function removeCartItem(Context $context, RemoveItemInput $input): RemoveItemOutput;
}
