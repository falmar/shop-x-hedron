<?php

namespace Tests\Integration\Domains\Carts;

use App\Domains\Carts\CartRepositoryEloquent;
use App\Domains\Carts\Entities\Cart;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartRepositoryEloquentTest extends TestCase
{
    use RefreshDatabase;

    public function testFindById_should_return_null(): void
    {
        // given
        $this->seed(\Database\Seeders\Tests\Carts\DomainSeeder::class);
        $this->expectsDatabaseQueryCount(1);

        /** @var CartRepositoryEloquent $repo */
        $repo = $this->app->make(CartRepositoryEloquent::class);

        // when
        $cart = $repo->findById('');

        // then
        $this->assertNull($cart, 'Cart should be null');
        $this->assertNotInstanceOf(Cart::class, $cart, 'Cart should not be an instance of Cart');
    }

    public function testFindById_should_return_an_entity(): void
    {
        // given
        $this->seed(\Database\Seeders\Tests\Carts\DomainSeeder::class);
        $this->expectsDatabaseQueryCount(1);

        /** @var CartRepositoryEloquent $repo */
        $repo = $this->app->make(CartRepositoryEloquent::class);

        // when
        $cart = $repo->findById('018c463c-2bf4-737d-90a4-4f9d03b51000');

        // then
        $this->assertInstanceOf(Cart::class, $cart, 'Cart should be an instance of Cart');


        $this->assertMagicEntity($cart);
    }

    public function testListBySessionId_should_return_empty_array(): void
    {
        // given
        $this->seed(\Database\Seeders\Tests\Carts\DomainSeeder::class);
        $this->expectsDatabaseQueryCount(1);

        /** @var CartRepositoryEloquent $repo */
        $repo = $this->app->make(CartRepositoryEloquent::class);

        // when
        $carts = $repo->listBySessionId('');

        // then
        $this->assertIsArray($carts, 'Carts should be an array');
        $this->assertCount(0, $carts, 'Carts should be empty');
    }

    public function testListBySessionId_should_return_items(): void
    {
        // given
        $this->seed(\Database\Seeders\Tests\Carts\DomainSeeder::class);
        $this->expectsDatabaseQueryCount(1);

        /** @var CartRepositoryEloquent $repo */
        $repo = $this->app->make(CartRepositoryEloquent::class);

        // when
        $carts = $repo->listBySessionId('018c463c-2bf4-737d-90a4-009d03b51100');

        // then
        $this->assertIsArray($carts, '$carts should be an array');
        $this->assertCount(1, $carts, '$carts should have 1 item');

        $this->assertMagicEntity($carts[0]);
    }

    public function testSave_should_create_a_new_entity(): void
    {
        // given
        $this->seed(\Database\Seeders\Tests\Carts\DomainSeeder::class);
        $this->expectsDatabaseQueryCount(2);

        /** @var CartRepositoryEloquent $repo */
        $repo = $this->app->make(CartRepositoryEloquent::class);

        $cart = new Cart();
        $cart->sessionId = '018c463c-2bf4-737d-90a4-009d03b51100';

        // when
        $result = $repo->save($cart);

        // then
        $this->assertTrue($result, 'Cart should be saved');

        $this->assertDatabaseHas('carts', [
            'id' => $cart->id,
            'session_id' => '018c463c-2bf4-737d-90a4-009d03b51100'
        ]);
    }

    public function testSave_should_update_an_existing_entity(): void
    {
        // given
        $this->seed(\Database\Seeders\Tests\Carts\DomainSeeder::class);
        $this->expectsDatabaseQueryCount(3);

        /** @var CartRepositoryEloquent $repo */
        $repo = $this->app->make(CartRepositoryEloquent::class);

        $cart = new Cart();
        $cart->id = '018c463c-2bf4-737d-90a4-4f9d03b51000';
        $cart->sessionId = '018c463c-2bf4-737d-90a4-009d03b51200';

        // when
        $result = $repo->save($cart);

        // then
        $this->assertTrue($result, 'Cart should be saved');

        $this->assertDatabaseHas('carts', [
            'id' => '018c463c-2bf4-737d-90a4-4f9d03b51000',
            'session_id' => '018c463c-2bf4-737d-90a4-009d03b51200',
        ]);
    }

    public function testSave_should_not_update_an_existing_entity(): void
    {
        // given
        $this->seed(\Database\Seeders\Tests\Carts\DomainSeeder::class);
        $this->expectsDatabaseQueryCount(2);

        /** @var CartRepositoryEloquent $repo */

        $repo = $this->app->make(CartRepositoryEloquent::class);

        $cart = new Cart();

        $cart->id = '018c463c-2bf4-737d-90a4-4f9d03b51000';
        $cart->sessionId = '018c463c-2bf4-737d-90a4-009d03b51100';

        // when
        $result = $repo->save($cart);

        // then
        $this->assertTrue($result, 'Cart should not be saved');

        $this->assertDatabaseHas('carts', [
            'id' => '018c463c-2bf4-737d-90a4-4f9d03b51000',
            'session_id' => '018c463c-2bf4-737d-90a4-009d03b51100'
        ]);
    }

    public function testDelete_should_delete_an_existing_entity(): void
    {
        // given
        $this->seed(\Database\Seeders\Tests\Carts\DomainSeeder::class);
        $this->expectsDatabaseQueryCount(2);

        /** @var CartRepositoryEloquent $repo */
        $repo = $this->app->make(CartRepositoryEloquent::class);

        // when
        $result = $repo->delete('018c463c-2bf4-737d-90a4-4f9d03b51000');

        // then
        $this->assertTrue($result, 'Cart should be deleted');

        $this->assertSoftDeleted('carts', [
            'id' => '018c463c-2bf4-737d-90a4-4f9d03b51000'
        ]);
    }

    public function assertMagicEntity(Cart $entity): void
    {
        // magic numbers yuck!

        $this->assertSame('018c463c-2bf4-737d-90a4-4f9d03b51000', $entity->id, 'Cart id does not match');
        $this->assertSame('018c463c-2bf4-737d-90a4-009d03b51100', $entity->sessionId, 'Cart session id does not match');
    }
}
