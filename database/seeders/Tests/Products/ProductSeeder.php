<?php

namespace Database\Seeders\Tests\Products;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'id' => '018c463c-2bf4-737d-90a4-4f9d03b50000',
                'brand' => 'Quechua',
                'name' => 'Chaqueta polar de montaña y trekking con capucha Hombre Quechua MH520 azul',
                'image_url' => 'https://example.com/image.jpg',
                'price' => 1999,
                'stock' => 100,
                'review_rating' => 4.60,
                'review_count' => 646
            ],
            [
                'id' => '018c463c-2bf4-737d-90a4-4f9d03b50001',
                'brand' => 'Pongori',
                'name' => 'Mesa ping pong exterior plegable tablero 4 mm Pongori PPT 500.2',
                'image_url' => 'https://example.com/image2.jpg',
                'price' => 36899,
                'stock' => 0,
                'review_rating' => 4.32,
                'review_count' => 39
            ],
            [
                'id' => '018c463c-2bf4-737d-90a4-4f9d03b50010',
                'brand' => 'Columbia',
                'name' => 'Zapatillas de montaña y trekking impermeables Hombre Columbia Redmond',
                'image_url' => 'https://example.com/image3.jpg',
                'price' => 7499,
                'stock' => 5,
                'review_rating' => 4.40,
                'review_count' => 500
            ]
        ];

        foreach ($products as $item) {
            if (Product::where('id', $item['id'])->exists()) {
                continue;
            }

            Product::create([
                'id' => $item['id'],
                'brand' => $item['brand'],
                'name' => $item['name'],
                'image_url' => $item['image_url'],
                'price' => $item['price'],
                'stock' => $item['stock'],
                'review_rating' => $item['review_rating'],
                'review_count' => $item['review_count']
            ]);
        }
    }
}
