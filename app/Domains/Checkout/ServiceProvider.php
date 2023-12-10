<?php

namespace App\Domains\Checkout;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(CheckoutServiceInterface::class, CheckoutService::class);
    }
}
