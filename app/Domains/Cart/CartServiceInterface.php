<?php

namespace App\Domains\Cart;

use App\Domains\Cart\Specs\AddItemInput;
use App\Domains\Cart\Specs\AddItemOutput;
use App\Domains\Cart\Specs\GetCartInput;
use App\Domains\Cart\Specs\GetCartOutput;
use App\Domains\Cart\Specs\ListCartsInput;
use App\Domains\Cart\Specs\ListCartsOutput;
use App\Domains\Cart\Specs\RemoveItemInput;
use App\Domains\Cart\Specs\RemoveItemOutput;
use App\Domains\Cart\Specs\UpdateItemInput;
use App\Domains\Cart\Specs\UpdateItemOutput;
use App\Libraries\Context\Context;

interface CartServiceInterface
{
    /**
     * List shopping carts
     *
     * @param Context $context
     * @param ListCartsInput $input
     * @return ListCartsOutput
     */
    public function listCarts(Context $context, ListCartsInput $input): ListCartsOutput;

    /**
     * Get cart by id
     *
     * @param Context $context
     * @param GetCartInput $input
     * @return GetCartOutput
     */
    public function getCart(Context $context, GetCartInput $input): GetCartOutput;

    /**
     * Add items cart
     *
     * @param Context $context
     * @param AddItemInput $input
     * @return AddItemOutput
     */
    public function addItemToCart(Context $context, AddItemInput $input): AddItemOutput;

    /**
     * Update a cart items
     *
     * @param Context $context
     * @param UpdateItemInput $input
     * @return UpdateItemOutput
     */
    public function updateCartItem(Context $context, UpdateItemInput $input): UpdateItemOutput;

    /**
     * Remove cart items
     *
     * @param Context $context
     * @param RemoveItemInput $input
     * @return RemoveItemOutput
     */
    public function removeItemFromCart(Context $context, RemoveItemInput $input): RemoveItemOutput;
}
