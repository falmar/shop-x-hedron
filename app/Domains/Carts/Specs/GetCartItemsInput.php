<?php

namespace App\Domains\Carts\Specs;

class GetCartItemsInput
{
    public string $cartId;

    public bool $withProduct = true;
}
