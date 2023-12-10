<?php

namespace Tests\Controllers\v1;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testCheckout_should_return_bad_request_item_product_mismatch(): void
    {
        // given
        $this->seed(\Database\Seeders\Tests\Products\DomainSeeder::class);
        $this->seed(\Database\Seeders\Tests\Carts\DomainSeeder::class);

        // when
        $response = $this->post('/api/v1/checkout/018c463c-2bf4-737d-90a4-4f9d03b51000');

        // then
        $response->assertStatus(400);
        $response->assertJsonStructure([
            'code',
            'message',
        ]);
        $response->assertJson([
            'code' => 'bad_request',
        ]);
    }

    public function testCheckout_should_return_bad_request_cart_not_found(): void
    {
        // given
        $this->seed(\Database\Seeders\Tests\Products\DomainSeeder::class);

        // when
        $response = $this->post('/api/v1/checkout/018c463c-2bf4-737d-90a4-009d03b51000');

        // then
        $response->assertStatus(404);
        $response->assertJsonStructure([
            'code',
            'message',
        ]);
        $response->assertJson([
            'code' => 'not_found',
        ]);
    }

    public function testCheckout_should_return_order(): void
    {
        // given
        $this->seed(\Database\Seeders\Tests\Products\DomainSeeder::class);
        $this->seed(\Database\Seeders\Tests\Carts\DomainSeeder::class);

        // when
        $response = $this->post('/api/v1/checkout/018c463c-2bf4-737d-90a4-4f9d03b51001');

        // then
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'cart_id',
                'amount'
            ],
        ]);
    }
}
