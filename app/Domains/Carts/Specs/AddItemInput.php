<?php

namespace App\Domains\Carts\Specs;

class AddItemInput
{
    public string $cartId;
    public string $productId;
    public int $quantity;
}
