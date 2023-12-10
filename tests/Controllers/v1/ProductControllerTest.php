<?php

namespace Tests\Controllers\v1;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testList_should_return_a_list_of_products(): void
    {
        // given
        $this->seed(\Database\Seeders\Tests\Products\DomainSeeder::class);

        // when
        $response = $this->get('/api/v1/products');

        // then
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'total',
            'data' => [
                '*' => [
                    'id',
                    'brand',
                    'name',
                    'image_url',
                    'price',
                    'review_rating',
                    'review_count',
                    'created_at',
                    'updated_at',
                ],
            ],
        ]);
    }
}
