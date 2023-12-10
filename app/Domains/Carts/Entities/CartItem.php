<?php

namespace App\Domains\Carts\Entities;

use App\Domains\Products\Entities\Product;

class CartItem implements \JsonSerializable
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

    // CROSSED-BOUNDARY
    public ?Product $product = null;
    // --

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

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        $data = [
            'id' => $this->id,
            'cart_id' => $this->cartId,
            'product_id' => $this->productId,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'created_at' => $this->createdAt->format(\DateTimeInterface::RFC3339),
            'updated_at' => $this->updatedAt->format(\DateTimeInterface::RFC3339),
            'deleted_at' => $this->deletedAt?->format(\DateTimeInterface::RFC3339),
        ];

        if ($this->product instanceof Product) {
            $data['product'] = $this->product;
        }

        return $data;
    }
}
