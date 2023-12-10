<?php

namespace App\Domains\Carts;

use App\Domains\Carts\Entities\Cart;

readonly class CartRepositoryEloquent implements CartRepositoryInterface
{
    public function __construct(private readonly \App\Models\Cart $eloquent)
    {
    }

    /**
     * @inheritDoc
     */
    public function findById(string $cardId): ?Cart
    {
        $eloquentItem = $this->eloquent->find($cardId);

        if (!$eloquentItem) {
            return null;
        }

        return Cart::fromArray($eloquentItem->toArray());
    }

    /**
     * @inheritDoc
     */
    public function listBySessionId(string $sessionId, array $options = []): array
    {
        $eloquentItems = $this->eloquent->where('session_id', $sessionId)->get();

        // TODO: from options take a limit/offset or cursor based pagination

        $items = [];
        foreach ($eloquentItems as $eloquentItem) {
            $items[] = Cart::fromArray($eloquentItem->toArray());
        }

        return $items;
    }

    /**
     * @inheritDoc
     */
    public function save(Cart $cart): bool
    {
        if ($cart->id ?? null) {
            $eloquentItem = $this->eloquent->find($cart->id);
        }

        if (!isset($eloquentItem)) {
            $eloquentItem = new $this->eloquent();
        }

        if ($cart->sessionId !== $eloquentItem->session_id) {
            $eloquentItem->session_id = $cart->sessionId;
        }

        $result = $eloquentItem->save();
        $cart->id = $eloquentItem->id;
        $cart->createdAt = $eloquentItem->created_at;
        $cart->updatedAt = $eloquentItem->updated_at;

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function delete(string $cartId): bool
    {
        return (new $this->eloquent())->where('id', $cartId)->delete();
    }
}
