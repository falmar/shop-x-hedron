<?php

namespace App\Domains\Products\Specs;

class GetProductInput
{
    public string $productId;

    public bool $inStock = false;
}
