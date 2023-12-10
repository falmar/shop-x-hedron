<?php

namespace App\Http\Controllers\v1;

use App\Domains\Carts\CartServiceInterface;
use App\Domains\Carts\Exceptions\CartItemNotFoundException;
use App\Domains\Carts\Exceptions\CartItemQuantityExceededStockException;
use App\Domains\Carts\Exceptions\CartItemQuantityException;
use App\Domains\Carts\Exceptions\CartNotFoundException;
use App\Domains\Carts\Exceptions\NoSessionIdException;
use App\Domains\Carts\Specs\AddItemInput;
use App\Domains\Carts\Specs\CreateCartInput;
use App\Domains\Carts\Specs\GetCartInput;
use App\Domains\Carts\Specs\GetCartItemsInput;
use App\Domains\Carts\Specs\ListCartsInput;
use App\Domains\Carts\Specs\RemoveItemInput;
use App\Domains\Carts\Specs\UpdateItemInput;
use App\Domains\Products\Exceptions\ProductOutOfStockException;
use App\Http\Controllers\Controller;
use App\Libraries\Context\AppContext;
use App\Libraries\Context\Context;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
        } catch (NoSessionIdException $e) {
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

            $carts = $this->cartService->getCart($context, $spec);

            return $response
                ->setStatusCode(200)
                ->setData([
                    'data' => $carts->cart,
                    'item_count' => $carts->itemCount,
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

    public function createCart(Request $request, JsonResponse $response): JsonResponse
    {
        try {
            /** @var Context $context */
            $context = AppContext::fromRequest($request);

            $spec = new CreateCartInput();

            $cart = $this->cartService->createCart($context, $spec)->cart;

            return $response
                ->setStatusCode(201)
                ->setData([
                    'data' => $cart,
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

    public function listCarItems(Request $request, JsonResponse $response): JsonResponse
    {
        try {
            /** @var Context $context */
            $context = AppContext::fromRequest($request);

            /** @var string $cartId */
            $cartId = $request->route()?->parameter('cart_id') ?? '';

            $spec = new GetCartItemsInput();
            $spec->cartId = $cartId;

            if ($request->get('with_products') == '0' || $request->get('with_products') == 'false   ') {
                $spec->withProduct = false;
            }

            $output = $this->cartService->listCardItems($context, $spec);

            return $response
                ->setStatusCode(200)
                ->setData([
                    'total' => $output->total,
                    'data' => $output->items,
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

            $item = $this->cartService->addCartItem($context, $spec)->item;

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

    public function updateCartItem(Request $request, JsonResponse $response): JsonResponse
    {
        try {
            /** @var Context $context */
            $context = AppContext::fromRequest($request);

            /** @var string $cartItemId */
            $cartItemId = $request->route()?->parameter('cart_item_id') ?? '';

            $spec = new UpdateItemInput();
            $spec->cartItemId = $cartItemId;
            $spec->quantity = (int)($request->get('quantity') ?? 1);

            $item = $this->cartService->updateCartItem($context, $spec)->item;

            return $response
                ->setStatusCode(200)
                ->setData([
                    'data' => $item,
                ]);
        } catch (ProductOutOfStockException|CartItemQuantityExceededStockException|CartItemQuantityException $e) {
            return $response
                ->setStatusCode(400)
                ->setData([
                    'code' => 'bad_request',
                    'message' => $e->getMessage(),
                ]);
        } catch (CartNotFoundException|CartItemNotFoundException $e) {
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

    public function removeCartItem(Request $request, JsonResponse $response): JsonResponse
    {
        try {
            /** @var Context $context */
            $context = AppContext::fromRequest($request);

            /** @var string $cartItemId */
            $cartItemId = $request->route()?->parameter('cart_item_id') ?? '';

            $spec = new RemoveItemInput();
            $spec->cartItemId = $cartItemId;

            $this->cartService->removeCartItem($context, $spec);

            return $response
                ->setStatusCode(204);
        } catch (CartNotFoundException|CartItemNotFoundException $e) {
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
}
