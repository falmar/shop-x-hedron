<?php

namespace App\Domains\Carts;

use App\Domains\Carts\Exceptions\InvalidSessionIdException;
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
use Ramsey\Uuid\Uuid;

readonly class CartService implements CartServiceInterface
{
    public function __construct(
        private CartRepositoryInterface $cartRepository,
        //        private CartItemRepositoryInterface $cartItemRepository,
    ) {
    }

    public function listCarts(Context $context, ListCartsInput $input): ListCartsOutput
    {
        $response = new ListCartsOutput();

        if (!$input->sessionId || !Uuid::isValid($input->sessionId)) {
            throw new InvalidSessionIdException('Session ID is required');
        }

        $carts = $this->cartRepository->listBySessionId($input->sessionId);
        $response->carts = $carts;

        return $response;
    }

    public function getCart(Context $context, GetCartInput $input): GetCartOutput
    {
        throw new \Exception('not implemented');
    }

    public function addItemToCart(Context $context, AddItemInput $input): AddItemOutput
    {
        throw new \Exception('not implemented');
    }

    public function updateCartItem(Context $context, UpdateItemInput $input): UpdateItemOutput
    {
        throw new \Exception('not implemented');
    }

    public function removeItemFromCart(Context $context, RemoveItemInput $input): RemoveItemOutput
    {
        throw new \Exception('not implemented');
    }
}
