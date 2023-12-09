<?php

namespace App\Domains\Carts;

use App\Domains\Carts\Entities\Cart;

interface CartRepositoryInterface
{
    /**
     * Get cart by id
     *
     * @param string $cardId
     * @return Cart|null
     */
    public function findById(string $cardId): ?Cart;

    /**
     * List carts
     *
     * @param string $sessionId
     * @param array<string, mixed> $options
     * @return list<Cart>
     */
    public function listBySessionId(string $sessionId, array $options = []): array;

    /**
     * Upsert a cart
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
