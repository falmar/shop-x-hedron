<?php

namespace App\Domains\Products;

use App\Domains\Products\Entities\Product;
use Carbon\Carbon;

readonly class ProductRepositoryEloquent implements ProductRepositoryInterface
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

    public function count(array $options): int
    {
        $query = $this->eloquent->query();

        if (isset($options['ids']) && is_array($options['ids']) && count($options['ids']) > 0) {
            $query->whereIn('id', $options['ids']);
        }

        return $query->count();
    }

    /**
     * @inheritDoc
     */
    public function list(array $options): array
    {
        $query = $this->eloquent->query();

        if (isset($options['ids']) && is_array($options['ids']) && count($options['ids']) > 0) {
            $query->whereIn('id', $options['ids']);
        }

        // TODO: from options take a limit/offset or cursor based pagination
        $eloquentItems = $query->get();

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
        $product->createdAt = $eloquentItem->created_at->toDateTimeImmutable();
        $product->updatedAt = $eloquentItem->updated_at->toDateTimeImmutable();

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
