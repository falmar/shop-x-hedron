<?php

namespace App\Domains\Carts\Entities;

class Cart
{
    public string $id;
    public string $sessionId;

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

        $obj->sessionId = $data['session_id'];

        $obj->createdAt = new \DateTimeImmutable($data['created_at']);
        $obj->updatedAt = new \DateTimeImmutable($data['updated_at']);
        $obj->deletedAt = isset($data['deleted_at']) ? new \DateTimeImmutable($data['deleted_at']) : null;

        return $obj;
    }
}
