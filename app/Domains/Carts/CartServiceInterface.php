<?php

namespace App\Domains\Carts;

use App\Domains\Carts\Specs\AddItemInput;
use App\Domains\Carts\Specs\AddItemOutput;
use App\Domains\Carts\Specs\GetCartInput;
use App\Domains\Carts\Specs\GetCartOutput;
use App\Domains\Carts\Specs\ListCartsInput;
use App\Domains\Carts\Specs\ListCartsOutput;
use App\Domains\Carts\Specs\RemoveItemInput;
use App\Domains\Carts\Specs\RemoveItemOutput;
use App\Domains\Carts\Specs\UpdateItemInput;
use App\Domains\Carts\Specs\UpdateItemOutput;
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
