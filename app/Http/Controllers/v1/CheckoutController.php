<?php

namespace App\Http\Controllers\v1;

use App\Domains\Carts\Exceptions\CartNotFoundException;
use App\Domains\Checkout\CheckoutServiceInterface;
use App\Domains\Checkout\Exceptions\NoItemsException;
use App\Domains\Checkout\Exceptions\ProductMismatchException;
use App\Domains\Checkout\Exceptions\QuantityMismatchException;
use App\Domains\Checkout\Specs\CheckoutInput;
use App\Http\Controllers\Controller;
use App\Libraries\Context\AppContext;
use App\Libraries\Context\Context;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function __construct(
        readonly private CheckoutServiceInterface $checkoutService
    ) {
    }


    public function checkout(Request $request, JsonResponse $response): JsonResponse
    {
        /** @var Context $context */
        $context = AppContext::fromRequest($request);

        /** @var string $cartId */
        $cartId = $request->route()?->parameter('cart_id') ?? '';

        try {
            $spec = new CheckoutInput();
            $spec->cartId = $cartId;

            $output = $this->checkoutService->checkout($context, $spec);

            return $response
                ->setStatusCode(200)
                ->setData([
                    'data' => $output->order,
                ]);
        } catch (CartNotFoundException $e) {
            return $response
                ->setStatusCode(404)
                ->setData([
                    'code' => 'cart_not_found',
                    'message' => $e->getMessage(),
                ]);
        } catch (QuantityMismatchException|ProductMismatchException|NoItemsException $e) {
            $code = 'bad_request';

            if ($e instanceof QuantityMismatchException) {
                $code = 'cart_item_quantity_mismatch';
            } elseif ($e instanceof ProductMismatchException) {
                $code = 'cart_item_product_mismatch';
            } elseif ($e instanceof NoItemsException) {
                $code = 'cart_is_empty';
            }

            return $response
                ->setStatusCode(400)
                ->setData([
                    'code' => $code,
                    'message' => $e->getMessage(),
                ]);
        } catch (\Throwable $th) {
            return $response
                ->setStatusCode(500)
                ->setData([
                    'code' => 'internal_server_error',
                    'message' => 'Internal Server Error',
                ]);
        }
    }
}
