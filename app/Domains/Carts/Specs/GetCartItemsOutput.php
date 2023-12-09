<?php

namespace App\Domains\Carts\Specs;

use App\Domains\Carts\ValueObjects\CartListItem;

class GetCartItemsOutput
{
    public int $total = 0;

    /** @var list<CartListItem> */
    public array $items = [];
}
