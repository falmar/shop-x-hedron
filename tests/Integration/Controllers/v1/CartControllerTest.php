<?php

namespace Tests\Integration\Controllers\v1;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testListCarts_should_not_return_a_list_of_carts(): void
    {
        // given
        $this->seed(\Database\Seeders\Tests\Carts\DomainSeeder::class);

        // when
        $response = $this->get('/api/v1/carts');

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

    public function testListCarts_should_return_a_list_of_carts(): void
    {
        // given
        $this->seed(\Database\Seeders\Tests\Carts\DomainSeeder::class);

        // when
        $response = $this->get('/api/v1/carts?session_id=018c463c-2bf4-737d-90a4-009d03b51100');

        // then
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'session_id',
                    'created_at',
                    'updated_at',
                ],
            ],
        ]);
    }

    public function testGetCart_should_return_bad_request_on_cart_id()
    {
        // given
        $this->seed(\Database\Seeders\Tests\Carts\DomainSeeder::class);

        // when
        $response = $this->get('/api/v1/carts/1');

        // then
        $response->assertStatus(400);
        $response->assertJsonStructure([
            'code',
            'message',
        ]);
    }

    public function testGetCart_should_return_not_found_on_cart_id()
    {
        // given
        $this->seed(\Database\Seeders\Tests\Carts\DomainSeeder::class);

        // when
        $response = $this->get('/api/v1/carts/018c463c-2bf4-737d-90a4-009d03b51100');

        // then
        $response->assertStatus(404);
        $response->assertJsonStructure([
            'code',
            'message',
        ]);
    }

    public function testGetCart_should_return_a_cart(): void
    {
        // given
        $this->seed(\Database\Seeders\Tests\Carts\DomainSeeder::class);

        $tests = [
            [
                'input' => [
                    'cart_id' => '018c463c-2bf4-737d-90a4-4f9d03b51000',
                    'query' => '?with_item_count=1'
                ],
                'expect_struct' => [
                    'data' => [
                        'id',
                        'session_id',
                        'created_at',
                        'updated_at',
                    ],
                    'item_count'
                ],
                'expect_data' => [
                    'data' => [
                        'id' => '018c463c-2bf4-737d-90a4-4f9d03b51000',
                    ],
                    'item_count' => 1,
                ]
            ],
            [
                'input' => [
                    'cart_id' => '018c463c-2bf4-737d-90a4-4f9d03b51000',
                    'query' => '?with_item_count=0'
                ],
                'expect_struct' => [
                    'data' => [
                        'id',
                        'session_id',
                        'created_at',
                        'updated_at',
                    ],
                    'item_count'
                ],
                'expect_data' => [
                    'data' => [
                        'id' => '018c463c-2bf4-737d-90a4-4f9d03b51000',
                    ],
                    'item_count' => null,
                ]
            ]
        ];

        foreach ($tests as $test) {
            // when
            $response = $this->get('/api/v1/carts/' . $test['input']['cart_id'] . $test['input']['query']);

            // then
            $response->assertStatus(200);
            $response->assertJsonStructure($test['expect_struct']);
            $response->assertJson($test['expect_data']);
        }
    }
}
