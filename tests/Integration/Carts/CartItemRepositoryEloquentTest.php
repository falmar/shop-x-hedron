<?php

namespace Tests\Integration\Carts;

use App\Domains\Carts\CartItemRepositoryEloquent;
use App\Domains\Carts\Entities\CartItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartItemRepositoryEloquentTest extends TestCase
{
    use RefreshDatabase;

    public function testFindById_should_return_null(): void
    {
        // given
        $this->seed(\Database\Seeders\Tests\Carts\DomainSeeder::class);
        $this->expectsDatabaseQueryCount(1);

        /** @var CartItemRepositoryEloquent $repo */
        $repo = $this->app->make(CartItemRepositoryEloquent::class);

        // when
        $cartItem = $repo->findById('');

        // then
        $this->assertNull($cartItem, 'CartItem should be null');
        $this->assertNotInstanceOf(CartItem::class, $cartItem, 'CartItem should not be an instance of CartItem');
    }

    public function testFindById_should_return_a_item(): void
    {
        // given
        $this->seed(\Database\Seeders\Tests\Carts\DomainSeeder::class);
        $this->expectsDatabaseQueryCount(1);

        /** @var CartItemRepositoryEloquent $repo */
        $repo = $this->app->make(CartItemRepositoryEloquent::class);

        // when
        $cartItem = $repo->findById('018c463c-2bf4-737d-90a4-4f9d03b52000');

        // then
        $this->assertInstanceOf(CartItem::class, $cartItem, 'CartItem should an instance of CartItem');


        $this->assertMagicEntity($cartItem);
    }

    public function testListByCartId_should_return_empty_array(): void
    {
        // given
        $this->seed(\Database\Seeders\Tests\Carts\DomainSeeder::class);
        $this->expectsDatabaseQueryCount(1);

        /** @var CartItemRepositoryEloquent $repo */
        $repo = $this->app->make(CartItemRepositoryEloquent::class);

        // when
        $cartItems = $repo->listByCartId('');

        // then
        $this->assertIsArray($cartItems, 'CartItems should be an array');
        $this->assertCount(0, $cartItems, 'CartItems should be empty');
    }

    public function testListByCartId_should_return_items(): void
    {
        // given
        $this->seed(\Database\Seeders\Tests\Carts\DomainSeeder::class);
        $this->expectsDatabaseQueryCount(1);

        /** @var CartItemRepositoryEloquent $repo */
        $repo = $this->app->make(CartItemRepositoryEloquent::class);

        // when
        $cartItems = $repo->listByCartId('018c463c-2bf4-737d-90a4-4f9d03b51000');

        // then
        $this->assertIsArray($cartItems, 'CartItems should be an array');
        $this->assertCount(1, $cartItems, 'CartItems should have 1 item');

        $this->assertMagicEntity($cartItems[0]);
    }

    public function testSave_should_create_a_new_cartItem(): void
    {
        // given
        $this->seed(\Database\Seeders\Tests\Carts\DomainSeeder::class);
        $this->expectsDatabaseQueryCount(2);

        /** @var CartItemRepositoryEloquent $repo */
        $repo = $this->app->make(CartItemRepositoryEloquent::class);

        $cartItem = new CartItem();
        $cartItem->cartId = '018c463c-2bf4-737d-90a4-4f9d03b51000';
        $cartItem->productId = '018c463c-2bf4-737d-90a4-4f9d03b50000';
        $cartItem->quantity = 1;
        $cartItem->price = 199;

        // when
        $result = $repo->save($cartItem);

        // then
        $this->assertTrue($result, 'CartItem should be saved');

        $this->assertDatabaseHas('cart_items', [
            'id' => $cartItem->id,
            'cart_id' => '018c463c-2bf4-737d-90a4-4f9d03b51000',
            'product_id' => '018c463c-2bf4-737d-90a4-4f9d03b50000',
            'quantity' => 1,
            'price' => 199
        ]);
    }

    public function testSave_should_update_an_existing_cartItem(): void
    {
        // given
        $this->seed(\Database\Seeders\Tests\Carts\DomainSeeder::class);
        $this->expectsDatabaseQueryCount(3);

        /** @var CartItemRepositoryEloquent $repo */
        $repo = $this->app->make(CartItemRepositoryEloquent::class);

        $cartItem = new CartItem();
        $cartItem->id = '018c463c-2bf4-737d-90a4-4f9d03b52000';
        $cartItem->cartId = '018c463c-2bf4-737d-90a4-4f9d03b51000';
        $cartItem->productId = '018c463c-2bf4-737d-90a4-4f9d03b50000';
        $cartItem->quantity = 10;
        $cartItem->price = 500;

        // when
        $result = $repo->save($cartItem);

        // then
        $this->assertTrue($result, 'CartItem should be saved');

        $this->assertDatabaseHas('cart_items', [
            'id' => '018c463c-2bf4-737d-90a4-4f9d03b52000',
            'cart_id' => '018c463c-2bf4-737d-90a4-4f9d03b51000',
            'product_id' => '018c463c-2bf4-737d-90a4-4f9d03b50000',
            'quantity' => 10,
            'price' => 500
        ]);
    }

    public function testSave_should_not_update_an_existing_cartItem(): void
    {
        // given
        $this->seed(\Database\Seeders\Tests\Carts\DomainSeeder::class);
        $this->expectsDatabaseQueryCount(2);

        /** @var CartItemRepositoryEloquent $repo */

        $repo = $this->app->make(CartItemRepositoryEloquent::class);

        $cartItem = new CartItem();

        $cartItem->id = '018c463c-2bf4-737d-90a4-4f9d03b52000';
        $cartItem->cartId = '018c463c-2bf4-737d-90a4-4f9d03b51000';
        $cartItem->productId = '018c463c-2bf4-737d-90a4-4f9d03b50000';
        $cartItem->quantity = 1;
        $cartItem->price = 199;

        // when
        $result = $repo->save($cartItem);

        // then
        $this->assertTrue($result, 'CartItem should not be saved');

        $this->assertDatabaseHas('cart_items', [
            'id' => '018c463c-2bf4-737d-90a4-4f9d03b52000',
            'cart_id' => '018c463c-2bf4-737d-90a4-4f9d03b51000',
            'product_id' => '018c463c-2bf4-737d-90a4-4f9d03b50000',
            'quantity' => 1,
            'price' => 199
        ]);
    }

    public function testDelete_should_delete_an_existing_cartItem(): void
    {
        // given
        $this->seed(\Database\Seeders\Tests\Carts\DomainSeeder::class);
        $this->expectsDatabaseQueryCount(2);

        /** @var CartItemRepositoryEloquent $repo */
        $repo = $this->app->make(CartItemRepositoryEloquent::class);

        // when
        $result = $repo->delete('018c463c-2bf4-737d-90a4-4f9d03b52000');

        // then
        $this->assertTrue($result, 'CartItem should be deleted');

        $this->assertSoftDeleted('cart_items', [
            'id' => '018c463c-2bf4-737d-90a4-4f9d03b52000'
        ]);
    }

    public function assertMagicEntity(CartItem $entity): void
    {
        // magic numbers yuck!

        $this->assertSame('018c463c-2bf4-737d-90a4-4f9d03b52000', $entity->id, 'CartItem id does not match');
        $this->assertSame('018c463c-2bf4-737d-90a4-4f9d03b51000', $entity->cartId, 'CartItem cartId does not match');
        $this->assertSame('018c463c-2bf4-737d-90a4-4f9d03b50000', $entity->productId, 'CartItem productId does not match');
        $this->assertSame(1, $entity->quantity, 'CartItem quantity does not match');
        $this->assertSame(199, $entity->price, 'CartItem price does not match');
    }
}
