<?php

namespace App\Domains\Products;

use App\Domains\Products\Exceptions\ProductNotFoundException;
use App\Domains\Products\Exceptions\ProductOutOfStockException;
use App\Domains\Products\Specs\GetProductInput;
use App\Domains\Products\Specs\GetProductOutput;
use App\Domains\Products\Specs\ListProductsInput;
use App\Domains\Products\Specs\ListProductsOutput;
use App\Libraries\Context\Context;

interface ProductServiceInterface
{
    /**
     * Get product by id
     *
     * @param Context $context
     * @param GetProductInput $input
     * @return GetProductOutput
     * @throws ProductNotFoundException|ProductOutOfStockException?
     */
    public function getProduct(Context $context, GetProductInput $input): GetProductOutput;

    /**
     * Get list of products or filter by options
     *
     * @param Context $context
     * @param ListProductsInput $input
     * @return ListProductsOutput
     */
    public function listProducts(Context $context, ListProductsInput $input): ListProductsOutput;
}
