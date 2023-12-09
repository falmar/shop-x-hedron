<?php

namespace App\Domains\Products;

use App\Domains\Products\Specs\ListProductsInput;
use App\Domains\Products\Specs\ListProductsOutput;
use App\Libraries\Context\Context;

readonly class ProductService implements ProductServiceInterface
{
    public function __construct(
        readonly private ProductRepositoryInterface $productRepository,
    ) {
    }

    public function list(Context $context, ListProductsInput $input): ListProductsOutput
    {
        $options = [
            'search' => $input->search,
        ];

        $count = $this->productRepository->count($options);
        $products = $this->productRepository->list($options);

        $output = new ListProductsOutput();
        $output->total = $count;
        $output->products = $products;

        return $output;
    }
}
