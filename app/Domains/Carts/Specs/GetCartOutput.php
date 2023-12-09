<?php

namespace App\Domains\Carts\Specs;

use App\Domains\Carts\Entities\Cart;

class GetCartOutput
{
    public Cart $cart;

    public ?int $itemCount = null;
}
