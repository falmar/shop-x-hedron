<?php

namespace Tests\Integration\Domains\Carts;

use App\Domains\Carts\CartService;
use App\Domains\Carts\Entities\Cart;
use App\Domains\Carts\Exceptions\InvalidUuidException;
use App\Domains\Carts\Specs\ListCartsInput;
use App\Libraries\Context\AppContext;
use Database\Seeders\Tests\Carts\DomainSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartServiceTest extends TestCase
{
    use RefreshDatabase;

    public function testListCards_should_throw_uuid_exception(): void
    {
        // given
        $this->seed(DomainSeeder::class);
        $context = AppContext::background();

        /** @var CartService $service */
        $service = $this->app->make(\App\Domains\Carts\CartService::class);

        $spec = new ListCartsInput();

        $tests = [
            [
                'input' => '',
                'expected' => InvalidUuidException::class,
            ],
            [
                'input' => 'invalid-cart-id',
                'expected' => InvalidUuidException::class,
            ],
        ];

        foreach ($tests as $test) {
            // given
            $spec->sessionId = $test['input'];

            // when
            try {
                $service->listCarts($context, $spec);

                $this->fail('Expected exception to be thrown');
            } catch (\Throwable $th) {
                $this->assertInstanceOf($test['expected'], $th);
            }
        }
    }

    public function testListCards_should_return_a_list_of_entities(): void
    {
        // given
        $this->seed(DomainSeeder::class);
        $context = AppContext::background();

        /** @var CartService $service */
        $service = $this->app->make(\App\Domains\Carts\CartService::class);

        $spec = new ListCartsInput();

        $tests = [
            [
                'input' => '018c463c-2bf4-737d-90a4-009d03b51100',
                'expected' => 1,
            ],
            [
                'input' => '996cb20e-1e35-42c5-83b4-36a2e58e538f',
                'expected' => 0,
            ],
        ];

        foreach ($tests as $test) {
            // given
            $spec->sessionId = $test['input'];

            // when
            $output = $service->listCarts($context, $spec);

            // then
            $this->assertCount($test['expected'], $output->carts);
        }
    }

    public function testGetCard_should_throw_uuid_exception(): void
    {
        // given
        $this->seed(DomainSeeder::class);
        $context = AppContext::background();

        /** @var CartService $service */
        $service = $this->app->make(\App\Domains\Carts\CartService::class);

        $tests = [
            [
                'input' => '',
                'expected' => InvalidUuidException::class,
            ],
            [
                'input' => 'invalid-cart-id',
                'expected' => InvalidUuidException::class,
            ],
        ];

        foreach ($tests as $test) {
            // given
            $spec = new \App\Domains\Carts\Specs\GetCartInput();
            $spec->cartId = $test['input'];

            // when
            try {
                $service->getCart($context, $spec);

                $this->fail('Expected exception to be thrown');
            } catch (\Throwable $th) {
                $this->assertInstanceOf($test['expected'], $th);
            }
        }
    }

    public function testGetCard_should_throw_cart_not_found_exception(): void
    {
        // given
        $this->seed(DomainSeeder::class);
        $context = AppContext::background();

        /** @var CartService $service */
        $service = $this->app->make(\App\Domains\Carts\CartService::class);

        $spec = new \App\Domains\Carts\Specs\GetCartInput();
        $spec->cartId = '996cb20e-1e35-42c5-83b4-36a2e58e538f';

        // when
        try {
            $service->getCart($context, $spec);

            $this->fail('Expected exception to be thrown');
        } catch (\Throwable $th) {
            $this->assertInstanceOf(\App\Domains\Carts\Exceptions\CartNotFoundException::class, $th);
        }
    }

    public function testGetCard_should_return_an_entity_with_options(): void
    {
        // given
        $this->seed(DomainSeeder::class);
        $context = AppContext::background();

        /** @var CartService $service */
        $service = $this->app->make(\App\Domains\Carts\CartService::class);

        $tests = [
            [
                'input' => [
                    'id' => '018c463c-2bf4-737d-90a4-4f9d03b51000',
                    'with_item_count' => true,
                ],
                'expect' => [
                    'item_count' => 1,
                ]
            ],
            [
                'input' => [
                    'id' => '018c463c-2bf4-737d-90a4-4f9d03b51000',
                    'with_item_count' => false,
                ],
                'expect' => [
                    'item_count' => null,
                ]
            ]
        ];

        $spec = new \App\Domains\Carts\Specs\GetCartInput();

        foreach ($tests as $test) {
            // given
            $spec->cartId = $test['input']['id'];
            $spec->withItemCount = $test['input']['with_item_count'];

            // when
            $output = $service->getCart($context, $spec);

            // then
            $this->assertInstanceOf(Cart::class, $output->cart);
            $this->assertSame($test['expect']['item_count'], $output->itemCount);
        }
    }
}
