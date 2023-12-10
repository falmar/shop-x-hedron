<?php

namespace Database\Seeders\Tests\Carts;

use App\Models\CartItem;
use Illuminate\Database\Seeder;

class CartItemSeeder extends Seeder
{
    public function run(): void
    {
        // depends on product
        $this->call(\Database\Seeders\Tests\Products\ProductSeeder::class);

        $items = [
            [
                'id' =>         '018c463c-2bf4-737d-90a4-4f9d03b52000',
                'cart_id' =>    '018c463c-2bf4-737d-90a4-4f9d03b51000',
                'product_id' => '018c463c-2bf4-737d-90a4-4f9d03b50000',
                'quantity' => 1,
                'price' => 199
            ],
            [
                'id' =>         '018c463c-2bf4-737d-90a4-4f9d03b52001',
                'cart_id' =>    '018c463c-2bf4-737d-90a4-4f9d03b51000',
                'product_id' => '018c463c-2bf4-737d-90a4-4f9d03b50001',
                'quantity' => 1,
                'price' => 5000
            ]
        ];

        foreach ($items as $item) {
            if (CartItem::where('id', $item['id'])->exists()) {
                continue;
            }

            CartItem::create($item);
        }
    }
}
