<?php

namespace App\Domains\Products;

use App\Domains\Products\Entities\Product;

interface ProductRepositoryInterface
{
    /**
     * Get a product by id
     *
     * @param string $productId
     * @return Product|null
     */
    public function findById(string $productId): ?Product;

    /**
     * List all products or filter by options
     * @param array<string, mixed> $options
     * @return Product[]
     */
    public function list(array $options): array;

    /**
     * Upsert a product
     *
     * @param Product $product
     * @return bool
     */
    public function save(Product $product): bool;

    /**
     * Delete a product by id
     *
     * @param string $productId
     * @return bool
     */
    public function delete(string $productId): bool;
}
