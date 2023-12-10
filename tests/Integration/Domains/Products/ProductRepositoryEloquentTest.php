<?php

namespace Tests\Integration\Domains\Products;

use App\Domains\Carts\CartRepositoryEloquent;
use App\Domains\Products\Entities\Product;
use App\Domains\Products\ProductRepositoryEloquent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductRepositoryEloquentTest extends TestCase
{
    use RefreshDatabase;

    public function testFindById_should_return_null(): void
    {
        // given
        $this->seed(\Database\Seeders\Tests\Products\DomainSeeder::class);
        $this->expectsDatabaseQueryCount(1);

        /** @var ProductRepositoryEloquent $repo */
        $repo = $this->app->make(CartRepositoryEloquent::class);

        // when
        $product = $repo->findById('');

        // then
        $this->assertNull($product, 'should be null');
        $this->assertNotInstanceOf(Product::class, $product, 'should not be an instance of Cart');
    }

    public function testFindById_should_return_a_item(): void
    {
        // given
        $this->seed(\Database\Seeders\Tests\Products\DomainSeeder::class);
        $this->expectsDatabaseQueryCount(1);

        /** @var ProductRepositoryEloquent $repo */
        $repo = $this->app->make(ProductRepositoryEloquent::class);

        // when
        $product = $repo->findById('018c463c-2bf4-737d-90a4-4f9d03b50000');

        // then
        $this->assertInstanceOf(Product::class, $product, 'should be an instance of Product');
        $this->assertMagicEntity($product);
    }

    public function testCount_should_return_total(): void
    {
        // given
        $this->seed(\Database\Seeders\Tests\Products\DomainSeeder::class);
        $this->expectsDatabaseQueryCount(1);

        /** @var ProductRepositoryEloquent $repo */
        $repo = $this->app->make(ProductRepositoryEloquent::class);

        // when
        $total = $repo->count([]);

        // then
        $this->assertEquals(3, $total);
    }

    public function testList_should_return_entities(): void
    {
        // given
        $this->seed(\Database\Seeders\Tests\Products\DomainSeeder::class);
        $this->expectsDatabaseQueryCount(1);

        /** @var ProductRepositoryEloquent $repo */
        $repo = $this->app->make(ProductRepositoryEloquent::class);

        // when
        $products = $repo->list([]);

        // then
        $this->assertNotEmpty($products, 'should not be empty');
        $this->assertIsArray($products, 'should be an array');
        $this->assertContainsOnlyInstancesOf(Product::class, $products, 'should be an array of Product');

        $this->assertMagicEntity($products[0]);
    }

    public function testList_should_return_entities_by_ids(): void {
        // given
        $this->seed(\Database\Seeders\Tests\Products\DomainSeeder::class);
        $this->expectsDatabaseQueryCount(1);

        /** @var ProductRepositoryEloquent $repo */
        $repo = $this->app->make(ProductRepositoryEloquent::class);

        // when
        $products = $repo->list([
            'ids' => [
                '018c463c-2bf4-737d-90a4-4f9d03b50000',
                '018c463c-2bf4-737d-90a4-4f9d03b50001',
            ]
        ]);

        // then
        $this->assertNotEmpty($products, 'should not be empty');
        $this->assertIsArray($products, 'should be an array');
        $this->assertContainsOnlyInstancesOf(Product::class, $products, 'should be an array of Product');
    }

    public function testList_should_not_return_entities_by_ids(): void {
        // given
        $this->seed(\Database\Seeders\Tests\Products\DomainSeeder::class);
        $this->expectsDatabaseQueryCount(1);

        /** @var ProductRepositoryEloquent $repo */
        $repo = $this->app->make(ProductRepositoryEloquent::class);

        // when
        $products = $repo->list([
            'ids' => [
                '018c463c-2bf4-737d-90a4-4f9d03b50002',
                'bad-uuid',
                null
            ]
        ]);

        // then
        $this->assertEmpty($products, 'should be empty');
        $this->assertIsArray($products, 'should be an array');
    }

    public function testSave_should_create_a_new_item(): void
    {
        // given
        $this->seed(\Database\Seeders\Tests\Products\DomainSeeder::class);
        $this->expectsDatabaseQueryCount(2);

        /** @var ProductRepositoryEloquent $repo */
        $repo = $this->app->make(ProductRepositoryEloquent::class);

        $product = new Product();
        $product->brand = 'Nike';
        $product->name = 'Some Nike shoes';
        $product->imageURL = 'example.com/image.jpg';
        $product->price = 19999;
        $product->stock = 1;
        $product->reviewRating = 5;
        $product->reviewCount = 1;

        // when
        $result = $repo->save($product);

        // then
        $this->assertTrue($result, 'should be true');
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'brand' => 'Nike',
            'name' => 'Some Nike shoes',
            'image_url' => 'example.com/image.jpg',
            'price' => 19999,
            'stock' => 1,
            'review_rating' => 5,
            'review_count' => 1
        ]);
    }

    public function testSave_should_update_an_existing_item(): void
    {
        // given
        $this->seed(\Database\Seeders\Tests\Products\DomainSeeder::class);
        $this->expectsDatabaseQueryCount(3);

        /** @var ProductRepositoryEloquent $repo */
        $repo = $this->app->make(ProductRepositoryEloquent::class);

        $product = new Product();
        $product->id = '018c463c-2bf4-737d-90a4-4f9d03b50000';
        $product->brand = 'Nike';
        $product->name = 'Some Nike shoes';
        $product->imageURL = 'example.com/image.jpg';
        $product->price = 19999;
        $product->stock = 1;
        $product->reviewRating = 5;
        $product->reviewCount = 1;

        // when
        $result = $repo->save($product);

        // then
        $this->assertTrue($result, 'should be true');
        $this->assertDatabaseHas('products', [
            'id' => '018c463c-2bf4-737d-90a4-4f9d03b50000',
            'brand' => 'Nike',
            'name' => 'Some Nike shoes',
            'image_url' => 'example.com/image.jpg',
            'price' => 19999,
            'stock' => 1,
            'review_rating' => 5,
            'review_count' => 1
        ]);
    }

    public function testSave_should_not_update_an_existing_item(): void
    {
        // given
        $this->seed(\Database\Seeders\Tests\Products\DomainSeeder::class);
        $this->expectsDatabaseQueryCount(2);

        /** @var ProductRepositoryEloquent $repo */
        $repo = $this->app->make(ProductRepositoryEloquent::class);

        $product = new Product();
        $product->id = '018c463c-2bf4-737d-90a4-4f9d03b50000';
        $product->brand = 'Quechua';
        $product->name = 'Chaqueta polar de montaña y trekking con capucha Hombre Quechua MH520 azul';
        $product->imageURL = 'https://example.com/image.jpg';
        $product->price = 1999;
        $product->stock = 100;
        $product->reviewRating = 4.60;
        $product->reviewCount = 646;

        // when
        $result = $repo->save($product);

        // then
        $this->assertTrue($result, 'should be true');
        $this->assertDatabaseHas('products', [
            'id' => '018c463c-2bf4-737d-90a4-4f9d03b50000',
            'brand' => 'Quechua',
            'name' => 'Chaqueta polar de montaña y trekking con capucha Hombre Quechua MH520 azul',
            'image_url' => 'https://example.com/image.jpg',
            'price' => 1999,
            'stock' => 100,
            'review_rating' => 4.60,
            'review_count' => 646
        ]);
    }

    public function testDelete_should_delete_an_existing_entity(): void
    {
        // given
        $this->seed(\Database\Seeders\Tests\Products\DomainSeeder::class);
        $this->expectsDatabaseQueryCount(2);

        /** @var ProductRepositoryEloquent $repo */
        $repo = $this->app->make(ProductRepositoryEloquent::class);

        // when
        $result = $repo->delete('018c463c-2bf4-737d-90a4-4f9d03b50000');

        // then
        $this->assertTrue($result, 'should be true');
        $this->assertSoftDeleted('products', [
            'id' => '018c463c-2bf4-737d-90a4-4f9d03b50000'
        ]);
    }

    public function testDelete_should_not_delete_an_existing_entity(): void
    {
        // given
        $this->seed(\Database\Seeders\Tests\Products\DomainSeeder::class);
        $this->expectsDatabaseQueryCount(2);

        /** @var ProductRepositoryEloquent $repo */
        $repo = $this->app->make(ProductRepositoryEloquent::class);

        // when
        $result = $repo->delete('');

        // then
        $this->assertFalse($result, 'should be false');

        $this->assertDatabaseCount('products', 3);
    }

    private function assertMagicEntity(Product $entity): void
    {
        // magic numbers yuck!

        $this->assertSame('018c463c-2bf4-737d-90a4-4f9d03b50000', $entity->id, 'Product id does not match');
        $this->assertSame('Quechua', $entity->brand, 'Product brand does not match');
        $this->assertSame(
            'Chaqueta polar de montaña y trekking con capucha Hombre Quechua MH520 azul',
            $entity->name,
            'Product name does not match'
        );
        $this->assertSame(
            'https://example.com/image.jpg',
            $entity->imageURL,
            'Product image_url does not match'
        );
        $this->assertSame(1999, $entity->price, 'Product price does not match');
        $this->assertSame(100, $entity->stock, 'Product stock does not match');
        $this->assertSame(4.60, $entity->reviewRating, 'Product review_rating does not match');
        $this->assertSame(646, $entity->reviewCount, 'Product review_count does not match');
    }
}
