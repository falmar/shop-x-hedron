<?php

namespace Tests\Integration\Domains\Carts;

use App\Domains\Carts\CartService;
use App\Domains\Carts\Exceptions\InvalidSessionIdException;
use App\Domains\Carts\Specs\ListCartsInput;
use App\Libraries\Context\AppContext;
use Database\Seeders\Tests\Carts\DomainSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartServiceTest extends TestCase
{
    use RefreshDatabase;

    public function testListCards_should_session_id_exception(): void
    {
        // given
        $this->seed(DomainSeeder::class);
        $context = AppContext::background();

        /** @var CartService $service */
        $service = $this->app->make(\App\Domains\Carts\CartService::class);

        $spec = new ListCartsInput();
        $sessionIds = [
            '',
            'invalid-session-id',
            null
        ];

        foreach ($sessionIds as $input) {
            // given
            $spec->sessionId = $input;

            // when
            try {
                $output = $service->listCarts($context, $spec);

                $this->fail('Expected exception to be thrown');
            } catch (\Throwable $th) {
                $this->assertInstanceOf(InvalidSessionIdException::class, $th);
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
}
