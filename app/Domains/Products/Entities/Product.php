<?php

namespace App\Domains\Products\Entities;

class Product implements \JsonSerializable
{
    public string $id;
    public string $brand;
    public string $name;
    public string $imageURL;
    public int $price;
    public int $stock;
    public int $reviewCount;
    public float $reviewRating;

    /** @var \DateTimeInterface */
    public \DateTimeInterface $createdAt;

    /** @var \DateTimeInterface */
    public \DateTimeInterface $updatedAt;

    /** @var \DateTimeInterface|null */
    public ?\DateTimeInterface $deletedAt = null;

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

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'brand' => $this->brand,
            'name' => $this->name,
            'image_url' => $this->imageURL,
            'price' => $this->price,
            'stock' => $this->stock,
            'review_count' => $this->reviewCount,
            'review_rating' => $this->reviewRating,
            'created_at' => $this->createdAt->format(\DateTimeInterface::RFC3339),
            'updated_at' => $this->updatedAt->format(\DateTimeInterface::RFC3339),
        ];
    }
}
