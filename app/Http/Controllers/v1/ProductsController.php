<?php

namespace App\Http\Controllers\v1;

use App\Domains\Products\ProductServiceInterface;
use App\Domains\Products\Specs\ListProductsInput;
use App\Http\Controllers\Controller;
use App\Libraries\Context\AppContext;
use App\Libraries\Context\Context;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function __construct(readonly private ProductServiceInterface $productService)
    {
    }

    public function listProducts(Request $request, JsonResponse $response): JsonResponse
    {
        /** @var Context $context */
        $context = AppContext::fromRequest($request);

        $spec = new ListProductsInput();
        $spec->search = $request->get('search', '');

        $output = $this->productService->list($context, $spec);

        return $response
            ->setStatusCode(200)
            ->setData([
                'total' => $output->total,
                'data' => $output->products,
            ]);
    }
}
