<?php

namespace App\Http\Controllers\v1;

use App\Domains\Carts\CartServiceInterface;
use App\Domains\Carts\Exceptions\CartItemQuantityExceededStockException;
use App\Domains\Carts\Exceptions\CartNotFoundException;
use App\Domains\Carts\Exceptions\InvalidUuidException;
use App\Domains\Carts\Specs\AddItemInput;
use App\Domains\Carts\Specs\GetCartInput;
use App\Domains\Carts\Specs\ListCartsInput;
use App\Domains\Products\Exceptions\ProductOutOfStockException;
use App\Http\Controllers\Controller;
use App\Libraries\Context\AppContext;
use App\Libraries\Context\Context;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    public function __construct(
        readonly CartServiceInterface $cartService
    ) {
    }

    public function listCarts(Request $request, JsonResponse $response): JsonResponse
    {
        try {
            /** @var Context $context */
            $context = AppContext::fromRequest($request);

            $spec = new ListCartsInput();
            $spec->sessionId = $request->get('session_id') ?? '';

            $carts = $this->cartService->listCarts($context, $spec);

            return $response
                ->setStatusCode(200)
                ->setData([
                    'data' => $carts->carts,
                ]);
        } catch (InvalidUuidException $e) {
            return $response
                ->setStatusCode(400)
                ->setData([
                    'code' => 'bad_request',
                    'message' => $e->getMessage(),
                ]);
        } catch (\Throwable $e) {
            return $response
                ->setStatusCode(500)
                ->setData([
                    'code' => 'internal_server_error',
                    'message' => 'Internal Server Error',
                ]);
        }
    }

    public function getCart(Request $request, JsonResponse $response): JsonResponse
    {
        try {
            /** @var Context $context */
            $context = AppContext::fromRequest($request);

            /** @var string $cartId */
            $cartId = $request->route()?->parameter('cart_id') ?? '';

            $spec = new GetCartInput();
            $spec->cartId = $cartId;
            $spec->withItemCount = $request->get('with_item_count') == '1';
            $spec->withItems = $request->get('with_items') == '1';

            $carts = $this->cartService->getCart($context, $spec);

            return $response
                ->setStatusCode(200)
                ->setData([
                    'data' => $carts->cart,
                    'item_count' => $carts->itemCount,
                ]);
        } catch (InvalidUuidException $e) {
            return $response
                ->setStatusCode(400)
                ->setData([
                    'code' => 'bad_request',
                    'message' => $e->getMessage(),
                ]);
        } catch (CartNotFoundException $e) {
            return $response
                ->setStatusCode(404)
                ->setData([
                    'code' => 'not_found',
                    'message' => $e->getMessage(),
                ]);
        } catch (\Throwable $e) {
            return $response
                ->setStatusCode(500)
                ->setData([
                    'code' => 'internal_server_error',
                    'message' => 'Internal Server Error',
                ]);
        }
    }

    public function addItemToCart(Request $request, JsonResponse $response): JsonResponse
    {
        try {
            /** @var Context $context */
            $context = AppContext::fromRequest($request);

            /** @var string $cartId */
            $cartId = $request->route()?->parameter('cart_id') ?? '';

            $spec = new AddItemInput();
            $spec->cartId = $cartId;
            $spec->productId = $request->get('product_id') ?? '';
            $spec->quantity = (int)($request->get('quantity') ?? 1);

            $item = $this->cartService->addItemToCart($context, $spec)->item;

            return $response
                ->setStatusCode(201)
                ->setData([
                    'data' => $item,
                ]);
        } catch (ProductOutOfStockException|CartItemQuantityExceededStockException $e) {
            return $response
                ->setStatusCode(400)
                ->setData([
                    'code' => 'bad_request',
                    'message' => $e->getMessage(),
                ]);
        } catch (\Throwable $e) {
            return $response
                ->setStatusCode(500)
                ->setData([
                    'code' => 'internal_server_error',
                    'message' => 'Internal Server Error',
                ]);
        }
    }
}
