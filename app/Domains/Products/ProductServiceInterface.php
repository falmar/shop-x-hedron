<?php

namespace App\Domains\Products;

use App\Domains\Products\Specs\ListProductsInput;
use App\Domains\Products\Specs\ListProductsOutput;
use App\Libraries\Context\Context;

interface ProductServiceInterface
{
    /**
     * Get list of products or filter by options
     *
     * @param Context $context
     * @param ListProductsInput $input
     * @return ListProductsOutput
     */
    public function list(Context $context, ListProductsInput $input): ListProductsOutput;
}
