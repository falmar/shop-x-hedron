<?php

namespace App\Domains\Products\Specs;

use App\Domains\Products\Entities\Product;

class ListProductsOutput
{
    /** @var int */
    public int $total = 0;
    /** @var Product[] */
    public array $products = [];
}
