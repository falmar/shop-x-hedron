<?php

namespace App\Domains\Carts;

use App\Domains\Carts\Entities\Cart;
use App\Domains\Carts\Entities\CartItem;
use App\Domains\Carts\Exceptions\CartItemNotFoundException;
use App\Domains\Carts\Exceptions\CartItemQuantityExceededStockException;
use App\Domains\Carts\Exceptions\CartItemQuantityException;
use App\Domains\Carts\Exceptions\CartNotFoundException;
use App\Domains\Carts\Exceptions\NoSessionIdException;
use App\Domains\Carts\Specs\AddItemInput;
use App\Domains\Carts\Specs\AddItemOutput;
use App\Domains\Carts\Specs\CreateCartInput;
use App\Domains\Carts\Specs\CreateCartOutput;
use App\Domains\Carts\Specs\GetCartInput;
use App\Domains\Carts\Specs\GetCartItemsInput;
use App\Domains\Carts\Specs\GetCartItemsOutput;
use App\Domains\Carts\Specs\GetCartOutput;
use App\Domains\Carts\Specs\ListCartsInput;
use App\Domains\Carts\Specs\ListCartsOutput;
use App\Domains\Carts\Specs\RemoveCartInput;
use App\Domains\Carts\Specs\RemoveCartOutput;
use App\Domains\Carts\Specs\RemoveItemInput;
use App\Domains\Carts\Specs\RemoveItemOutput;
use App\Domains\Carts\Specs\UpdateItemInput;
use App\Domains\Carts\Specs\UpdateItemOutput;
use App\Domains\Products\ProductServiceInterface;
use App\Domains\Products\Specs\GetProductInput;
use App\Domains\Products\Specs\ListProductsInput;
use App\Libraries\Context\Context;
use Ramsey\Uuid\Uuid;

readonly class CartService implements CartServiceInterface
{
    public function __construct(
        private CartRepositoryInterface $cartRepository,
        private CartItemRepositoryInterface $cartItemRepository,
        private ProductServiceInterface $productService,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function listCarts(Context $context, ListCartsInput $input): ListCartsOutput
    {
        $response = new ListCartsOutput();

        if (!$input->sessionId) {
            throw new NoSessionIdException('Session ID is required');
        }

        $carts = $this->cartRepository->listBySessionId($input->sessionId);
        $response->carts = $carts;

        return $response;
    }

    /**
     * @inheritDoc
     */
    public function getCart(Context $context, GetCartInput $input): GetCartOutput
    {
        $cart = $this->cartRepository->findById($input->cartId);
        if (!$cart) {
            throw new CartNotFoundException("Cart [{$input->cartId}] not found");
        }

        $output = new GetCartOutput();
        $output->cart = $cart;

        if ($input->withItemCount) {
            $output->itemCount = $this->cartItemRepository->countByCartId($cart->id);
        }

        return $output;
    }

    /**
     * @inheritDoc
     */
    public function createCart(Context $context, CreateCartInput $input): CreateCartOutput
    {
        $cart = new Cart();
        // session id would come from the request; but for simplicity we generate one here
        $cart->sessionId = Uuid::uuid7()->toString();
        $this->cartRepository->save($cart);

        $output = new CreateCartOutput();
        $output->cart = $cart;

        return $output;
    }

    /**
     * @inheritDoc
     */
    public function removeCart(Context $context, RemoveCartInput $input): RemoveCartOutput
    {
        $cart = $this->cartRepository->findById($input->cartId);
        if (!$cart) {
            throw new CartNotFoundException("Cart [{$input->cartId}] not found");
        }

        $this->cartRepository->delete($cart->id);

        // don't delete items because can be fetched by order for display

        return new RemoveCartOutput();
    }

    /**
     * @inheritDoc
     */
    public function listCardItems(Context $context, GetCartItemsInput $input): GetCartItemsOutput
    {
        // get cart
        $cart = $this->cartRepository->findById($input->cartId);
        if (!$cart) {
            throw new CartNotFoundException("Cart [{$input->cartId}] not found");
        }

        $output = new GetCartItemsOutput();
        $output->items = $this->cartItemRepository->listByCartId($cart->id);

        if ($input->withProduct) {
            $output->items = $this->attachProducts($context, $output->items);
        }

        return $output;
    }

    /**
     * @inheritDoc
     */
    public function addCartItem(Context $context, AddItemInput $input): AddItemOutput
    {
        // get cart
        $cart = $this->cartRepository->findById($input->cartId);
        if (!$cart) {
            throw new CartNotFoundException("Cart [{$input->cartId}] not found");
        }

        // get product for cart item
        $productSpec = new GetProductInput();
        $productSpec->productId = $input->productId;
        $productSpec->inStock = true;

        // let exception by raised and handled by caller
        $product = $this->productService->getProduct($context, $productSpec)->product;

        $cartItem = $this->cartItemRepository->findByCartIdAndProductId($cart->id, $product->id);

        if (!$cartItem) {
            // create new cart item
            $cartItem = new CartItem();
            $cartItem->cartId = $cart->id;
            $cartItem->productId = $product->id;
            $cartItem->quantity = $input->quantity;
            $cartItem->price = $product->price;
        } else {
            // update existing cart item
            $cartItem->quantity += $input->quantity;
        }

        if ($product->stock < $cartItem->quantity) {
            throw new CartItemQuantityExceededStockException(
                "product [{$product->id}] stock is below requested quantity"
            );
        }

        $this->cartItemRepository->save($cartItem);

        // attach product to cart item
        $cartItem->product = $product;

        $output = new AddItemOutput();
        $output->item = $cartItem;

        return $output;
    }

    /**
     * @inheritDoc
     */
    public function updateCartItem(Context $context, UpdateItemInput $input): UpdateItemOutput
    {
        if ($input->quantity <= 0) {
            throw new CartItemQuantityException('Quantity must be greater than 0');
        }

        // get cart item
        $cartItem = $this->cartItemRepository->findById($input->cartItemId);
        if (!$cartItem) {
            throw new CartItemNotFoundException("Cart item [{$input->cartItemId}] not found");
        }

        // get product for cart item
        $productSpec = new GetProductInput();
        $productSpec->productId = $cartItem->productId;
        $productSpec->inStock = true;

        // let exception by raised and handled by caller
        $product = $this->productService->getProduct($context, $productSpec)->product;

        if ($product->stock < $input->quantity) {
            throw new CartItemQuantityExceededStockException(
                "product [{$product->id}] stock is below requested quantity"
            );
        }

        $cartItem->quantity = $input->quantity;
        $this->cartItemRepository->save($cartItem);

        $output = new UpdateItemOutput();
        $output->item = $cartItem;
        $output->item->product = $product;

        return $output;
    }

    /**
     * @inheritDoc
     */
    public function removeCartItem(Context $context, RemoveItemInput $input): RemoveItemOutput
    {
        // get cart item
        $cartItem = $this->cartItemRepository->findById($input->cartItemId);
        if (!$cartItem) {
            throw new CartItemNotFoundException("Cart item [{$input->cartItemId}] not found");
        }

        $this->cartItemRepository->delete($cartItem->id);

        return new RemoveItemOutput();
    }

    /**
     * @param Context $context
     * @param list<CartItem> $cartItems
     * @return list<CartItem>
     */
    private function attachProducts(Context $context, array $cartItems): array
    {
        $spec = new ListProductsInput();
        // get product ids from cart items

        $spec->ids = array_filter(
            array_map(fn ($item) => $item->productId, $cartItems),
            fn ($id) => is_string($id) && Uuid::isValid($id)
        );

        if (!count($spec->ids)) {
            return $cartItems;
        }

        // make a dictionary of products by id
        $products = array_reduce(
            $this->productService->listProducts($context, $spec)->products,
            fn ($acc, $item) => [...$acc, $item->id => $item],
            []
        );

        foreach ($cartItems as $item) {
            $item->product = $products[$item->productId] ?? null;
        }

        // attach product to cart item
        return $cartItems;
    }
}
