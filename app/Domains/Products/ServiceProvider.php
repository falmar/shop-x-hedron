<?php

namespace App\Domains\Products;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ProductRepositoryInterface::class, ProductRepositoryEloquent::class);
        $this->app->bind(ProductServiceInterface::class, ProductService::class);
    }
}
