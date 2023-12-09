<?php

namespace App\Domains\Products;

use App\Domains\Products\Entities\Product;

class ProductRepositoryEloquent implements ProductRepositoryInterface
{
    public function __construct(
        private readonly \App\Models\Product $eloquent
    ) {
    }

    /**
     * @inheritDoc
     */
    public function findById(string $productId): ?Product
    {
        $eloquentItem = $this->eloquent->find($productId);

        if (!$eloquentItem) {
            return null;
        }

        return Product::fromArray($eloquentItem->toArray());
    }

    /**
     * @inheritDoc
     */
    public function list(array $options): array
    {
        $eloquentItems = $this->eloquent->get();

        // TODO: from options take a limit/offset or cursor based pagination

        $items = [];
        foreach ($eloquentItems as $eloquentItem) {
            $items[] = Product::fromArray($eloquentItem->toArray());
        }

        return $items;
    }

    /**
     * @inheritDoc
     */
    public function save(Product $product): bool
    {
        if ($product->id ?? null) {
            $eloquentItem = $this->eloquent->find($product->id);
        }

        if (!isset($eloquentItem)) {
            $eloquentItem = new $this->eloquent();
        }

        if ($product->name !== $eloquentItem->name) {
            $eloquentItem->name = $product->name;
        }
        if ($product->brand !== $eloquentItem->brand) {
            $eloquentItem->brand = $product->brand;
        }
        if ($product->imageURL !== $eloquentItem->image_url) {
            $eloquentItem->image_url = $product->imageURL;
        }
        if ($product->price !== $eloquentItem->price) {
            $eloquentItem->price = $product->price;
        }
        if ($product->stock !== $eloquentItem->stock) {
            $eloquentItem->stock = $product->stock;
        }
        if ($product->reviewRating !== $eloquentItem->review_rating) {
            $eloquentItem->review_rating = $product->reviewRating;
        }
        if ($product->reviewCount !== $eloquentItem->review_count) {
            $eloquentItem->review_count = $product->reviewCount;
        }

        if (!$eloquentItem->save()) {
            return false;
        }

        $product->id = $eloquentItem->id;

        return true;
    }

    /**
     * @inheritDoc
     */
    public function delete(string $productId): bool
    {
        return (new $this->eloquent())->where('id', $productId)->delete();
    }
}
