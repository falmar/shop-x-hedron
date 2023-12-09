<?php

namespace Tests\Integration\Domains\Products;

use App\Domains\Products\ProductServiceInterface;
use App\Domains\Products\Specs\ListProductsInput;
use App\Libraries\Context\AppContext;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductServiceTest extends TestCase
{
    use RefreshDatabase;

    public function testList_should_return_a_list_of_products(): void
    {
        // given
        $context = AppContext::background();
        $this->seed(\Database\Seeders\Tests\Products\DomainSeeder::class);

        /** @var ProductServiceInterface $service  */
        $service = $this->app->make(ProductServiceInterface::class);
        $spec = new ListProductsInput();

        // when
        $output = $service->list($context, $spec);

        // then
        $this->assertEquals(2, $output->total);
        $this->assertCount(2, $output->products);
        $this->assertContainsOnlyInstancesOf(\App\Domains\Products\Entities\Product::class, $output->products);
    }
}
