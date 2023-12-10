<?php

namespace App\Domains\Checkout\ValueObjects;

class CheckoutOrder implements \JsonSerializable
{
    public function __construct(
        public string $id,
        public string $cartId,
        public int $amount
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'cart_id' => $this->cartId,
            'amount' => $this->amount,
        ];
    }
}
