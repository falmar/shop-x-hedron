<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = json_decode(
            file_get_contents(base_path('storage/seeds/products.json')),
            true,
            JSON_THROW_ON_ERROR
        );

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
                'review_count' => $item['review_count'],
                'deleted_at' => $item['deleted_at'] ?? null
            ]);
        }
    }
}
