<?php

namespace App\Domains\Carts\Specs;

class GetCartInput
{
    public string $cartId;

    public bool $withItemCount = false;
    public bool $withItems = false;
}
