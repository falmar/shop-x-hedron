<?php

namespace Tests\Integration\Domains\Products;

use App\Domains\Products\Exceptions\ProductNotFoundException;
use App\Domains\Products\Exceptions\ProductOutOfStockException;
use App\Domains\Products\ProductServiceInterface;
use App\Domains\Products\Specs\GetProductInput;
use App\Domains\Products\Specs\ListProductsInput;
use App\Libraries\Context\AppContext;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductServiceTest extends TestCase
{
    use RefreshDatabase;

    public function testGetProduct_should_return_not_found_exception(): void
    {
        // given
        $context = AppContext::background();
        $this->seed(\Database\Seeders\Tests\Products\DomainSeeder::class);

        /** @var ProductServiceInterface $service */
        $service = $this->app->make(ProductServiceInterface::class);
        $spec = new GetProductInput();

        $tests = [
            'not-found',
            '018c463c-2bf4-737d-90a4-4f9d03b51000',
            ''
        ];

        foreach ($tests as $test) {
            // given
            $spec->productId = $test;

            try {
                // when
                $service->getProduct($context, $spec);
            } catch (ProductNotFoundException $e) {
                $this->assertEquals("Product [{$test}] not found", $e->getMessage());
                continue;
            } catch (ProductOutOfStockException) {
                $this->fail("ProductOutOfStockException was thrown");
            }

            $this->fail("ProductNotFoundException was not thrown");
        }
    }

    public function testGetProduct_should_return_out_of_stock_exception(): void
    {
        // given
        $context = AppContext::background();
        $this->seed(\Database\Seeders\Tests\Products\DomainSeeder::class);

        /** @var ProductServiceInterface $service */
        $service = $this->app->make(ProductServiceInterface::class);
        $spec = new GetProductInput();
        $spec->inStock = true;

        $tests = [
            '018c463c-2bf4-737d-90a4-4f9d03b50001',
        ];

        foreach ($tests as $test) {
            // given
            $spec->productId = $test;

            try {
                // when
                $service->getProduct($context, $spec);
            } catch (ProductNotFoundException) {
                $this->fail("ProductNotFoundException was thrown");
            } catch (ProductOutOfStockException $e) {
                $this->assertEquals("Product [{$test}] is out of stock", $e->getMessage());
                continue;
            }

            $this->fail("ProductOutOfStockException was not thrown");
        }
    }

    public function testGetProduct_should_return_a_product(): void
    {
        // given
        $context = AppContext::background();
        $this->seed(\Database\Seeders\Tests\Products\DomainSeeder::class);

        /** @var ProductServiceInterface $service */
        $service = $this->app->make(ProductServiceInterface::class);
        $spec = new GetProductInput();

        $tests = [
            '018c463c-2bf4-737d-90a4-4f9d03b50000',
            '018c463c-2bf4-737d-90a4-4f9d03b50001',
        ];

        foreach ($tests as $test) {
            // given
            $spec->productId = $test;

            try {
                // when
                $output = $service->getProduct($context, $spec);
            } catch (ProductNotFoundException) {
                $this->fail("ProductNotFoundException was thrown");
            } catch (ProductOutOfStockException) {
                $this->fail("ProductOutOfStockException was thrown");
            }

            // then
            $this->assertInstanceOf(\App\Domains\Products\Entities\Product::class, $output->product);
        }
    }

    public function testList_should_return_a_list_of_products(): void
    {
        // given
        $context = AppContext::background();
        $this->seed(\Database\Seeders\Tests\Products\DomainSeeder::class);

        /** @var ProductServiceInterface $service */
        $service = $this->app->make(ProductServiceInterface::class);
        $spec = new ListProductsInput();

        // when
        $output = $service->listProducts($context, $spec);

        // then
        $this->assertEquals(3, $output->total);
        $this->assertCount(3, $output->products);
        $this->assertContainsOnlyInstancesOf(\App\Domains\Products\Entities\Product::class, $output->products);
    }
}
