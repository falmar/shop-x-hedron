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
                'image_url' => 'https://contents.mediadecathlon.com/p2327732/k$df7ae2aa5a9687dd6203bfbc828a9265/sq/chaqueta-polar-de-montana-y-trekking-con-capucha-hombre-quechua-mh520-azul.jpg?format=auto&f=800x0',
                'price' => 1999,
                'stock' => 100,
                'review_rating' => 4.60,
                'review_count' => 646
            ],
            [
                'id' => '018c463c-2bf4-737d-90a4-4f9d03b50001',
                'brand' => 'Pongori',
                'name' => 'Mesa ping pong exterior plegable tablero 4 mm Pongori PPT 500.2',
                'image_url' => 'https://contents.mediadecathlon.com/p2272561/k$d08780fabfc81791e18a9f61b925a297/sq/mesa-ping-pong-exterior-plegable-tablero-4-mm-pongori-ppt-5002.jpg?format=auto&f=800x0',
                'price' => 36899,
                'stock' => 0,
                'review_rating' => 4.32,
                'review_count' => 39
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
