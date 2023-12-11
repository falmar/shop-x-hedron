<?php

namespace Tests\Controllers\v1;

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
            'code' => 'no_session_id',
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

    public function testGetCart_should_return_not_found_on_cart_id(): void
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

        $response->assertJson([
            'code' => 'cart_not_found',
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
                    'item_count' => 2,
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

    public function testAddToCart_should_return_bad_request_out_of_stock(): void
    {
        // given
        $this->seed(\Database\Seeders\Tests\Carts\DomainSeeder::class);

        // when
        $response = $this->post('/api/v1/carts/018c463c-2bf4-737d-90a4-4f9d03b51000/items', [
            'product_id' => '018c463c-2bf4-737d-90a4-4f9d03b50001',
            'quantity' => 1,
        ]);

        // then
        $response->assertStatus(400);
        $response->assertJsonStructure([
            'code',
            'message',
        ]);

        $response->assertJson([
            'code' => 'product_out_of_stock',
        ]);
    }

    public function testAddToCart_should_return_bad_request_exceeded_stock(): void
    {
        // given
        $this->seed(\Database\Seeders\Tests\Carts\DomainSeeder::class);


        // when
        $response = $this->post('/api/v1/carts/018c463c-2bf4-737d-90a4-4f9d03b51000/items', [
            'product_id' => '018c463c-2bf4-737d-90a4-4f9d03b50010',
            'quantity' => 10,
        ]);

        // then
        $response->assertStatus(400);
        $response->assertJsonStructure([
            'code',
            'message',
        ]);

        $response->assertJson([
            'code' => 'cart_item_quantity_exceeded_stock',
        ]);
    }

    public function testAddToCart_should_create_item(): void
    {
        // given
        $this->seed(\Database\Seeders\Tests\Carts\DomainSeeder::class);


        // when
        $response = $this->post('/api/v1/carts/018c463c-2bf4-737d-90a4-4f9d03b51000/items', [
            'product_id' => '018c463c-2bf4-737d-90a4-4f9d03b50000',
            'quantity' => 1,
        ]);

        // then
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'cart_id',
                'product_id',
                'quantity',
                'product' => [
                    'id',
                    'name',
                    'price',
                    'stock',
                    'image_url'
                ]
            ],
        ]);
    }

    public function testListCardItems_should_return_not_found_on_cart_id(): void
    {
        // given
        $this->seed(\Database\Seeders\Tests\Carts\DomainSeeder::class);

        $inputs = [
            '018c463c-2bf4-737d-90a4-009d03b50010'
        ];

        foreach ($inputs as $input) {
            // when
            $response = $this->get('/api/v1/carts/' . $input . '/items');

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
    }

    public function testListCardItems_should_return_a_list_of_items_with_products(): void
    {
        // given
        $this->seed(\Database\Seeders\Tests\Carts\DomainSeeder::class);

        // when
        $response = $this->get('/api/v1/carts/018c463c-2bf4-737d-90a4-4f9d03b51000/items');

        // then
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'cart_id',
                    'product_id',
                    'quantity',
                    'price',
                    'product' => [
                        'id',
                        'name',
                        'price',
                        'stock',
                        'image_url'
                    ]
                ],
            ],
        ]);
    }

    public function testListCardItems_should_return_a_list_of_items_without_products(): void
    {
        // given
        $this->seed(\Database\Seeders\Tests\Carts\DomainSeeder::class);

        $inputs = [
            '0',
            'false'
        ];

        foreach ($inputs as $input) {
            // when
            $response = $this->get('/api/v1/carts/018c463c-2bf4-737d-90a4-4f9d03b51000/items?with_products=' . $input);

            // then
            $response->assertStatus(200);
            $response->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'cart_id',
                        'product_id',
                        'quantity',
                        'price',
                    ],
                ],
            ]);
            $response->assertJsonMissing([
                'product' => [
                    'id',
                    'name',
                    'price',
                    'stock',
                    'image_url'
                ]
            ]);
        }
    }

    public function testUpdateItem_should_return_not_found_on_item_id(): void
    {
        // given
        $this->seed(\Database\Seeders\Tests\Carts\DomainSeeder::class);

        $inputs = [
            '018c463c-2bf4-737d-90a4-009d03b50010'
        ];

        foreach ($inputs as $input) {
            // when
            $response = $this->post('/api/v1/carts/018c463c-2bf4-737d-90a4-4f9d03b51000/items/' . $input, [
                'quantity' => 1,
            ]);

            // then
            $response->assertStatus(404);
            $response->assertJsonStructure([
                'code',
                'message',
            ]);

            $response->assertJson([
                'code' => 'cart_item_not_found',
            ]);
        }
    }

    public function testUpdateItem_should_return_bad_request_on_negative_quantity(): void
    {
        // given
        $this->seed(\Database\Seeders\Tests\Carts\DomainSeeder::class);

        $inputs = [
            '0',
            '-1'
        ];

        foreach ($inputs as $input) {
            // when
            $response = $this->post(
                '/api/v1/carts/018c463c-2bf4-737d-90a4-4f9d03b51000/items/018c463c-2bf4-737d-90a4-4f9d03b52000',
                [
                    'quantity' => $input,
                ]
            );

            // then
            $response->assertStatus(400);
            $response->assertJsonStructure([
                'code',
                'message',
            ]);

            $response->assertJson([
                'code' => 'cart_item_quantity',
            ]);
        }
    }

    public function testUpdateItem_should_return_bad_request_exceeded_stock(): void
    {
        // given
        $this->seed(\Database\Seeders\Tests\Carts\DomainSeeder::class);


        // when
        $response = $this->post(
            '/api/v1/carts/018c463c-2bf4-737d-90a4-4f9d03b51000/items/018c463c-2bf4-737d-90a4-4f9d03b52000',
            [
                'quantity' => 101,
            ]
        );

        // then
        $response->assertStatus(400);
        $response->assertJsonStructure([
            'code',
            'message',
        ]);

        $response->assertJson([
            'code' => 'cart_item_quantity_exceeded_stock',
        ]);
    }

    public function testUpdateItem_should_return_bad_request_out_of_stock(): void
    {
        // given
        $this->seed(\Database\Seeders\Tests\Carts\DomainSeeder::class);


        // when
        $response = $this->post(
            '/api/v1/carts/018c463c-2bf4-737d-90a4-4f9d03b51000/items/018c463c-2bf4-737d-90a4-4f9d03b52001',
            [
                'quantity' => 1,
            ]
        );

        // then
        $response->assertStatus(400);
        $response->assertJsonStructure([
            'code',
            'message',
        ]);

        $response->assertJson([
            'code' => 'product_out_of_stock',
        ]);
    }

    public function testUpdateItem_should_update_item(): void
    {
        // given
        $this->seed(\Database\Seeders\Tests\Carts\DomainSeeder::class);


        // when
        $response = $this->post(
            '/api/v1/carts/018c463c-2bf4-737d-90a4-4f9d03b51000/items/018c463c-2bf4-737d-90a4-4f9d03b52000',
            [
                'quantity' => 2,
            ]
        );

        // then
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'cart_id',
                'product_id',
                'quantity',
                'price',
                'product' => [
                    'id',
                    'name',
                    'price',
                    'stock',
                    'image_url'
                ]
            ],
        ]);
    }

    public function testRemoveItem_should_throw_item_not_found(): void
    {
        // given
        $this->seed(\Database\Seeders\Tests\Carts\DomainSeeder::class);

        $inputs = [
            '018c463c-2bf4-737d-90a4-009d03b50010'
        ];

        foreach ($inputs as $input) {
            // when
            $response = $this->delete('/api/v1/carts/018c463c-2bf4-737d-90a4-4f9d03b51000/items/' . $input);

            // then
            $response->assertStatus(404);
            $response->assertJsonStructure([
                'code',
                'message',
            ]);

            $response->assertJson([
                'code' => 'cart_item_not_found',
            ]);
        }
    }

    public function testRemoveItem_should_remove_item(): void
    {
        // given
        $this->seed(\Database\Seeders\Tests\Carts\DomainSeeder::class);

        // when
        $response = $this->delete(
            '/api/v1/carts/018c463c-2bf4-737d-90a4-4f9d03b51000/items/018c463c-2bf4-737d-90a4-4f9d03b52000'
        );

        // then
        $response->assertStatus(204);
    }

    public function testCreateCart_should_create_entity(): void
    {
        // given
        $this->seed(\Database\Seeders\Tests\Carts\DomainSeeder::class);

        // when
        $response = $this->post('/api/v1/carts');

        // then
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'session_id',
                'created_at',
                'updated_at',
            ],
        ]);
    }
}
