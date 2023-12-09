<?php

namespace App\Domains\Carts;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(CartRepositoryInterface::class, CartRepositoryEloquent::class);
        $this->app->bind(CartItemRepositoryInterface::class, CartItemRepositoryEloquent::class);
        $this->app->bind(CartServiceInterface::class, CartService::class);
    }
}
