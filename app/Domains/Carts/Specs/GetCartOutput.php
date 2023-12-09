<?php

namespace App\Domains\Carts\Specs;

use App\Domains\Carts\Entities\Cart;
use App\Domains\Carts\ValueObjects\CartListItems;

class GetCartOutput
{
    public Cart $cart;

    public ?int $itemCount = null;
}
