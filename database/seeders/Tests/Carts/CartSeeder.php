<?php

namespace Database\Seeders\Tests\Carts;

use App\Models\Cart;
use Illuminate\Database\Seeder;

class CartSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'id' => '018c463c-2bf4-737d-90a4-4f9d03b51000',
                'session_id' => '018c463c-2bf4-737d-90a4-009d03b51100',
            ],
            [
                'id' => '018c463c-2bf4-737d-90a4-4f9d03b51001',
                'session_id' => '018c463c-2bf4-737d-90a4-009d03b51101',
            ]
        ];

        foreach ($items as $item) {
            if (Cart::where('id', $item['id'])->exists()) {
                continue;
            }

            Cart::create($item);
        }
    }
}
