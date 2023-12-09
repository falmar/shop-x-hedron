<?php

namespace App\Domains\Carts\Specs;

use App\Domains\Carts\Entities\Cart;

class ListCartsOutput
{
    /** @var list<Cart> */
    public array $carts = [];
}
