<?php

namespace Tests\Domains\Checkout;

use App\Domains\Checkout\CheckoutService;
use App\Domains\Checkout\Exceptions\ProductMismatchException;
use App\Libraries\Context\AppContext;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class CheckoutServiceTest extends TestCase
{
    use RefreshDatabase;

    public function testCheckout_should_throw_mismatch_out_of_stock(): void
    {
        // given
        $context = AppContext::background();
        $this->seed(\Database\Seeders\Tests\Carts\DomainSeeder::class);

        /** @var CheckoutService $service */
        $service = $this->app->make(CheckoutService::class);

        $input = new \App\Domains\Checkout\Specs\CheckoutInput();
        $input->cartId = '018c463c-2bf4-737d-90a4-4f9d03b51000';


        // when
        $this->expectException(\App\Domains\Checkout\Exceptions\QuantityMismatchException::class);
        $service->checkout($context, $input);

        // then
        $this->fail('Should throw an exception');
    }

    public function testCheckout_should_throw_mismatch_quantity(): void
    {
        // given
        $context = AppContext::background();
        $this->seed(\Database\Seeders\Tests\Carts\DomainSeeder::class);

        /** @var CheckoutService $service */
        $service = $this->app->make(CheckoutService::class);

        $input = new \App\Domains\Checkout\Specs\CheckoutInput();
        $input->cartId = '018c463c-2bf4-737d-90a4-4f9d03b51002';


        // when
        $this->expectException(\App\Domains\Checkout\Exceptions\QuantityMismatchException::class);
        $service->checkout($context, $input);

        // then
        $this->fail('Should throw an exception');
    }

    public function testCheckout_should_throw_mismatch_on_product_not_found(): void {
        // given
        $context = AppContext::background();
        $this->seed(\Database\Seeders\Tests\Carts\DomainSeeder::class);

        /** @var CheckoutService $service */
        $service = $this->app->make(CheckoutService::class);

        $input = new \App\Domains\Checkout\Specs\CheckoutInput();
        $input->cartId = '018c463c-2bf4-737d-90a4-4f9d03b51003';

        // when
        $this->expectException(\App\Domains\Checkout\Exceptions\ProductMismatchException::class);
        $service->checkout($context, $input);

        // then
        $this->fail('Should throw an exception');
    }

    public function testCheckout_should_throw_no_items_exception(): void {
        // given
        $context = AppContext::background();
        $this->seed(\Database\Seeders\Tests\Carts\DomainSeeder::class);

        /** @var CheckoutService $service */
        $service = $this->app->make(CheckoutService::class);

        $input = new \App\Domains\Checkout\Specs\CheckoutInput();
        $input->cartId = '018c463c-2bf4-737d-90a4-4f9d03b51004';

        // when
        $this->expectException(\App\Domains\Checkout\Exceptions\NoItemsException::class);
        $service->checkout($context, $input);

        // then
        $this->fail('Should throw an exception');
    }

    public function testCheckout_should_deduct_stock(): void
    {
        // given
        $context = AppContext::background();
        $this->seed(\Database\Seeders\Tests\Carts\DomainSeeder::class);

        /** @var CheckoutService $service */
        $service = $this->app->make(CheckoutService::class);

        $input = new \App\Domains\Checkout\Specs\CheckoutInput();
        $input->cartId = '018c463c-2bf4-737d-90a4-4f9d03b51001';

        // when
        $output = $service->checkout($context, $input);

        // then
        $this->assertTrue(Uuid::isValid($output->order->id));
        $this->assertEquals('018c463c-2bf4-737d-90a4-4f9d03b51001', $output->order->cartId);
        $this->assertEquals(398, $output->order->amount);

        $this->assertDatabaseHas('products', [
            'id' => '018c463c-2bf4-737d-90a4-4f9d03b50000',
            'stock' => 98
        ]);
    }

    public function testCheckout_should_delete_cart(): void
    {
        // given
        $context = AppContext::background();
        $this->seed(\Database\Seeders\Tests\Carts\DomainSeeder::class);

        /** @var CheckoutService $service */
        $service = $this->app->make(CheckoutService::class);

        $input = new \App\Domains\Checkout\Specs\CheckoutInput();
        $input->cartId = '018c463c-2bf4-737d-90a4-4f9d03b51001';

        // when
        $output = $service->checkout($context, $input);

        // then
        $this->assertSoftDeleted('carts', [
            'id' => '018c463c-2bf4-737d-90a4-4f9d03b51001'
        ]);
    }
}
