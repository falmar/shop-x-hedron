<?php

namespace App\Domains\Products\Specs;

class UpdateProductStockInput
{
    public string $productId;
    public int $quantity;
}
