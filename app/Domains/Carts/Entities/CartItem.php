<?php

namespace App\Domains\Carts\Entities;

class CartItem
{
    public string $id;
    public string $cartId;
    public string $productId;
    public int $quantity;
    public int $price;

    /** @var \DateTimeImmutable */
    public \DateTimeImmutable $createdAt;

    /** @var \DateTimeImmutable */
    public \DateTimeImmutable $updatedAt;

    /** @var \DateTimeImmutable|null */
    public ?\DateTimeImmutable $deletedAt;

    /**
     * @param array<string, mixed> $data
     * @return self
     * @throws \Exception
     */
    public static function fromArray(array $data): self
    {
        $obj = new self();
        $obj->id = $data['id'] ?? null;

        $obj->cartId = $data['cart_id'];
        $obj->productId = $data['product_id'];
        $obj->quantity = $data['quantity'];
        $obj->price = $data['price'];

        $obj->createdAt = new \DateTimeImmutable($data['created_at']);
        $obj->updatedAt = new \DateTimeImmutable($data['updated_at']);
        $obj->deletedAt = isset($data['deleted_at']) ? new \DateTimeImmutable($data['deleted_at']) : null;

        return $obj;
    }
}
