<?php

namespace App\Domains\Cart;

use App\Domains\Cart\Entities\Cart;

interface CartRepositoryInterface
{
    /**
     * Get cart by id
     *
     * @param string $cardId
     * @return Cart|null
     */
    public function getById(string $cardId): ?Cart;

    /**
     * List carts
     *
     * @param array<string, mixed> $options
     * @return list<Cart>
     */
    public function list(array $options = []): array;

    /**
     * Upsert carts
     *
     * @param Cart $cart
     * @return bool
     */
    public function save(Cart $cart): bool;

    /**
     * Delete cart
     * @param string $cartId
     * @return bool
     */
    public function delete(string $cartId): bool;
}
