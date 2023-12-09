<?php

namespace Tests\Integration\Controllers\v1;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testList_should_not_return_a_list_of_carts(): void
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

    public function testList_should_return_a_list_of_carts(): void
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
}
