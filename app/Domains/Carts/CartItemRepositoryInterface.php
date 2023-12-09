<?php

namespace App\Domains\Carts;

use App\Domains\Carts\Entities\CartItem;

interface CartItemRepositoryInterface
{
    /**
     * Get cart item by id
     *
     * @param string $itemId
     * @return CartItem|null
     */
    public function getById(string $itemId): ?CartItem;

    /**
     * Get cart items by cart id
     *
     * @param string $cartId
     * @return CartItem[]
     */
    public function listByCartId(string $cartId): array;

    /**
     * Upsert cart item
     *
     * @param CartItem $item
     * @return bool
     */
    public function save(CartItem $item): bool;

    /**
     * delete cart item
     *
     * @param string $itemId
     * @return bool
     */
    public function delete(string $itemId): bool;
}
