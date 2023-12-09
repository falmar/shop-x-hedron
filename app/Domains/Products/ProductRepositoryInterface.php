<?php

namespace App\Domains\Products;

use App\Domains\Products\Entities\Product;

interface ProductRepositoryInterface
{
    /**
     * Get a product by id
     *
     * @param int $id
     * @return Product|null
     */
    public function getById(int $id): ?Product;

    /**
     * List all products or filter by options
     * @param array<string, mixed> $options
     * @return Product[]
     */
    public function list(array $options): array;
}
