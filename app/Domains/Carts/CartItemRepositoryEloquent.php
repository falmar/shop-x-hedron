<?php

namespace App\Domains\Carts;

use App\Domains\Carts\Entities\CartItem;

class CartItemRepositoryEloquent implements CartItemRepositoryInterface
{
    public function __construct(private readonly \App\Models\CartItem $eloquent)
    {
    }

    /**
     * @inheritDoc
     */
    public function findById(string $itemId): ?CartItem
    {
        $eloquentItem = $this->eloquent->find($itemId);

        if (!$eloquentItem) {
            return null;
        }

        return CartItem::fromArray($eloquentItem->toArray());
    }

    /**
     * @inheritDoc
     */
    public function listByCartId(string $cartId, array $options = []): array
    {
        $eloquentItems = $this->eloquent->where('cart_id', $cartId)->get();

        // TODO: from options take a limit/offset or cursor based pagination

        $items = [];
        foreach ($eloquentItems as $eloquentItem) {
            $items[] = CartItem::fromArray($eloquentItem->toArray());
        }

        return $items;
    }

    /**
     * @inheritDoc
     */
    public function save(CartItem $item): bool
    {
        if ($item->id ?? null) {
            $eloquentItem = $this->eloquent->find($item->id);
        }

        if (!isset($eloquentItem)) {
            $eloquentItem = new $this->eloquent();
        }

        if ($item->cartId !== $eloquentItem->cart_id) {
            $eloquentItem->cart_id = $item->cartId;
        }
        if ($item->productId !== $eloquentItem->product_id) {
            $eloquentItem->product_id = $item->productId;
        }
        if ($item->quantity !== $eloquentItem->quantity) {
            $eloquentItem->quantity = $item->quantity;
        }
        if ($item->price !== $eloquentItem->price) {
            $eloquentItem->price = $item->price;
        }

        $result = $eloquentItem->save();
        $item->id = $eloquentItem->id;

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function delete(string $itemId): bool
    {
        return (new $this->eloquent())->where('id', $itemId)->delete();
    }
}
