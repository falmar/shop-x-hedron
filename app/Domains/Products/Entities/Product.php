<?php

namespace App\Domains\Products\Entities;

class Product
{
    public string $id;
    public string $brand;
    public string $name;
    public string $imageURL;
    public int $price;
    public int $stock;
    public int $reviewCount;
    public float $reviewRating;

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

        $obj->brand = $data['brand'];
        $obj->name = $data['name'];
        $obj->imageURL = $data['image_url'];
        $obj->price = $data['price'];
        $obj->stock = $data['stock'];
        $obj->reviewCount = $data['review_count'];
        $obj->reviewRating = $data['review_rating'];

        $obj->createdAt = new \DateTimeImmutable($data['created_at']);
        $obj->updatedAt = new \DateTimeImmutable($data['updated_at']);
        $obj->deletedAt = isset($data['deleted_at']) ? new \DateTimeImmutable($data['deleted_at']) : null;

        return $obj;
    }
}
