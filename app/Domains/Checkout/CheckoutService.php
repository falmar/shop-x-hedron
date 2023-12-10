<?php

namespace App\Domains\Checkout;

use App\Domains\Carts\CartServiceInterface;
use App\Domains\Carts\Specs\GetCartInput;
use App\Domains\Carts\Specs\GetCartItemsInput;
use App\Domains\Carts\Specs\RemoveCartInput;
use App\Domains\Checkout\Exceptions\CartItemProductMismatchException;
use App\Domains\Checkout\Exceptions\NoCartItemsException;
use App\Domains\Checkout\Specs\CheckoutInput;
use App\Domains\Checkout\Specs\CheckoutOutput;
use App\Domains\Checkout\ValueObjects\CheckoutOrder;
use App\Domains\Products\Entities\Product;
use App\Domains\Products\ProductServiceInterface;
use App\Domains\Products\Specs\ListProductsInput;
use App\Domains\Products\Specs\UpdateProductStockInput;
use App\Libraries\Context\Context;
use Ramsey\Uuid\Uuid;

readonly class CheckoutService implements CheckoutServiceInterface
{
    public function __construct(
        private ProductServiceInterface $productService,
        private CartServiceInterface $cartService
    ) {
    }

    /**
     * @inheritDoc
     */
    public function checkout(Context $context, CheckoutInput $input): CheckoutOutput
    {
        $cartSpec = new GetCartInput();
        $cartSpec->cartId = $input->cartId;
        $cartSpec->withItemCount = true;

        // let raise an exception if the cart is not found
        $cartOutput = $this->cartService->getCart($context, $cartSpec);

        if ($cartOutput->itemCount === 0) {
            throw new NoCartItemsException('Cart is empty');
        }

        $itemsSpec = new GetCartItemsInput();
        $itemsSpec->cartId = $cartOutput->cart->id;

        // let raise an exception if the cart is not found
        $items = $this->cartService->listCardItems($context, $itemsSpec)->items;

        $productIds = array_map(fn ($item) => $item->productId, $items);

        $productSpec = new ListProductsInput();
        $productSpec->ids = $productIds;

        $products = $this->productService->listProducts($context, $productSpec)->products;
        $products = array_reduce($products, function ($acc, $product) {
            $acc[$product->id] = $product;
            return $acc;
        }, []);

        // check is available
        foreach ($items as $item) {
            $item->product = $products[$item->productId];

            if (!$item->product) {
                throw new CartItemProductMismatchException('Product not found');
            } elseif ($item->product->stock === 0) {
                throw new CartItemProductMismatchException('Product is out of stock');
            } elseif ($item->product->stock < $item->quantity) {
                throw new CartItemProductMismatchException('Product stock is too low');
            }
        }

        // this should be within a transaction

        // update products stock
        $amount = 0;
        $updateStockSpec = new UpdateProductStockInput();

        foreach ($items as $item) {
            /** @var Product $product */
            $product = $item->product;
            $updateStockSpec->productId = $item->productId;
            $updateStockSpec->quantity = $item->quantity * -1;

            // we already have the product at hand no need to fetch it again
            $context = $context->withAttribute('product', $product);

            $this->productService->updateProductStock($context, $updateStockSpec);

            $amount += $item->price * $item->quantity;
        }

        $deleteSpec = new RemoveCartInput();
        $deleteSpec->cartId = $cartOutput->cart->id;
        $this->cartService->removeCart($context, $deleteSpec);
        // --

        // create order
        $order = new CheckoutOrder(
            id: Uuid::uuid7()->toString(),
            cartId: $input->cartId,
            amount: $amount
        );

        $out = new CheckoutOutput();
        $out->order = $order;

        return $out;
    }
}
