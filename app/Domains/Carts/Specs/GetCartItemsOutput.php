<?php

namespace App\Domains\Carts\Specs;

use App\Domains\Carts\Entities\CartItem;

class GetCartItemsOutput
{
    public int $total = 0;

    /** @var list<CartItem> */
    public array $items = [];
}
