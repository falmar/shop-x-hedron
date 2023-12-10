<?php

namespace App\Domains\Products;

use App\Domains\Products\Exceptions\ProductNotFoundException;
use App\Domains\Products\Exceptions\ProductOutOfStockException;
use App\Domains\Products\Specs\GetProductInput;
use App\Domains\Products\Specs\GetProductOutput;
use App\Domains\Products\Specs\ListProductsInput;
use App\Domains\Products\Specs\ListProductsOutput;
use App\Domains\Products\Specs\UpdateProductStockInput;
use App\Domains\Products\Specs\UpdateProductStockOutput;
use App\Libraries\Context\Context;

readonly class ProductService implements ProductServiceInterface
{
    public function __construct(
        private ProductRepositoryInterface $productRepository,
    ) {
    }

    public function getProduct(Context $context, GetProductInput $input): GetProductOutput
    {
        $product = $this->productRepository->findById($input->productId);
        if (!$product) {
            throw new ProductNotFoundException("Product [{$input->productId}] not found");
        } elseif ($input->inStock && $product->stock <= 0) {
            throw new ProductOutOfStockException("Product [{$input->productId}] is out of stock");
        }

        $output = new GetProductOutput();
        $output->product = $product;

        return $output;
    }

    public function updateProductStock(Context $context, UpdateProductStockInput $input): UpdateProductStockOutput
    {
        $product = $context->getAttribute('product');
        if (!$product || $product->id !== $input->productId) {
            $product = $this->productRepository->findById($input->productId);
        }

        if (!$product) {
            throw new ProductNotFoundException("Product [{$input->productId}] not found");
        }

        $product->stock += $input->quantity;

        $this->productRepository->save($product);

        $output = new UpdateProductStockOutput();
        $output->product = $product;

        return $output;
    }

    public function listProducts(Context $context, ListProductsInput $input): ListProductsOutput
    {
        $options = [
            'search' => $input->search,
            'ids' => $input->ids,
        ];

        $count = $this->productRepository->count($options);
        $products = $this->productRepository->list($options);

        $output = new ListProductsOutput();
        $output->total = $count;
        $output->products = $products;

        return $output;
    }
}
