<?php

namespace App\Domains\Carts;

use App\Domains\Carts\Exceptions\CartNotFoundException;
use App\Domains\Carts\Exceptions\InvalidUuidException;
use App\Domains\Carts\Specs\AddItemInput;
use App\Domains\Carts\Specs\AddItemOutput;
use App\Domains\Carts\Specs\GetCartInput;
use App\Domains\Carts\Specs\GetCartItemsInput;
use App\Domains\Carts\Specs\GetCartItemsOutput;
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
        private CartItemRepositoryInterface $cartItemRepository,
    ) {
    }

    public function listCarts(Context $context, ListCartsInput $input): ListCartsOutput
    {
        $response = new ListCartsOutput();

        if (!Uuid::isValid($input->sessionId)) {
            throw new InvalidUuidException('Session ID is required');
        }

        $carts = $this->cartRepository->listBySessionId($input->sessionId);
        $response->carts = $carts;

        return $response;
    }

    public function getCart(Context $context, GetCartInput $input): GetCartOutput
    {
        if (!Uuid::isValid($input->cartId)) {
            throw new InvalidUuidException('Cart ID is required');
        }

        $cart = $this->cartRepository->findById($input->cartId);

        if (!$cart) {
            throw new CartNotFoundException("Cart [{$input->cartId}] not found");
        }

        $output = new GetCartOutput();
        $output->cart = $cart;

        if ($input->withItemCount || $input->withItems) {
            $output->itemCount = $this->cartItemRepository->countByCartId($cart->id);
        }

        return $output;
    }

    public function listCardItems(Context $context, GetCartItemsInput $input): GetCartItemsOutput
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
