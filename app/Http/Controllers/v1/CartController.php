<?php

namespace App\Http\Controllers\v1;

use App\Domains\Carts\CartServiceInterface;
use App\Domains\Carts\Exceptions\InvalidSessionIdException;
use App\Domains\Carts\Specs\ListCartsInput;
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
            $spec->sessionId = $request->get('session_id', '');

            $carts = $this->cartService->listCarts($context, $spec);

            return $response
                ->setStatusCode(200)
                ->setData([
                    'data' => $carts->carts,
                ]);
        } catch (InvalidSessionIdException $e) {
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
