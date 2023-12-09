<?php

namespace App\Domains\Carts\ValueObjects;

use App\Domains\Carts\Entities\CartItem;
use App\Domains\Products\Entities\Product;

class CartListItem implements \JsonSerializable
{
    public CartItem $cartItem;
    public Product $product;

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        $data = $this->cartItem->jsonSerialize();
        $data['product'] = $this->product->jsonSerialize();

        return $data;
    }
}
