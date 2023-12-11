<?php

namespace Tests\Controllers\v1;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testCheckout_should_return_mismatch_out_of_stock(): void
    {
        // given
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
            'code' => 'cart_item_quantity_mismatch',
        ]);
    }
    public function testCheckout_should_return_quantity_mismatch_quantity(): void
    {
        // given
        $this->seed(\Database\Seeders\Tests\Carts\DomainSeeder::class);

        // when
        $response = $this->post('/api/v1/checkout/018c463c-2bf4-737d-90a4-4f9d03b51002');

        // then
        $response->assertStatus(400);
        $response->assertJsonStructure([
            'code',
            'message',
        ]);
        $response->assertJson([
            'code' => 'cart_item_quantity_mismatch',
        ]);
    }

    public function testCheckout_should_return_product_not_found(): void
    {
        // given
        $this->seed(\Database\Seeders\Tests\Carts\DomainSeeder::class);

        // when
        $response = $this->post('/api/v1/checkout/018c463c-2bf4-737d-90a4-4f9d03b51003');

        // then
        $response->assertStatus(400);
        $response->assertJsonStructure([
            'code',
            'message',
        ]);
        $response->assertJson([
            'code' => 'cart_item_product_mismatch',
        ]);
    }

    public function testCheckout_should_return_bad_request_no_items(): void
    {
        // given
        $this->seed(\Database\Seeders\Tests\Carts\DomainSeeder::class);

        // when
        $response = $this->post('/api/v1/checkout/018c463c-2bf4-737d-90a4-4f9d03b51004');

        // then
        $response->assertStatus(400);
        $response->assertJsonStructure([
            'code',
            'message',
        ]);
        $response->assertJson([
            'code' => 'cart_is_empty',
        ]);
    }

    public function testCheckout_should_return_bad_request_cart_not_found(): void
    {
        // given

        // when
        $response = $this->post('/api/v1/checkout/018c463c-2bf4-737d-90a4-009d03b51000');

        // then
        $response->assertStatus(404);
        $response->assertJsonStructure([
            'code',
            'message',
        ]);
        $response->assertJson([
            'code' => 'cart_not_found',
        ]);
    }

    public function testCheckout_should_return_order(): void
    {
        // given
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
