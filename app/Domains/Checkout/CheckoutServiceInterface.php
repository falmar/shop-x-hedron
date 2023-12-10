<?php

namespace App\Domains\Checkout;

use App\Domains\Carts\Exceptions\CartNotFoundException;
use App\Domains\Checkout\Exceptions\CartItemProductMismatchException;
use App\Domains\Checkout\Exceptions\NoCartItemsException;
use App\Domains\Checkout\Specs\CheckoutInput;
use App\Domains\Checkout\Specs\CheckoutOutput;
use App\Libraries\Context\Context;

interface CheckoutServiceInterface
{
    /**
     * Checkout the cart, verify the items and product stock
     * and return the order
     * @param Context $context
     * @param CheckoutInput $input
     * @return CheckoutOutput
     * @throws CartItemProductMismatchException|NoCartItemsException
     * @throws CartNotFoundException
     */
    public function checkout(Context $context, CheckoutInput $input): CheckoutOutput;
}
