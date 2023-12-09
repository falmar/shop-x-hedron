<?php

namespace App\Libraries\Context;

use Illuminate\Support\ServiceProvider;

class ContextProvider extends ServiceProvider
{
    /**
     * @inheritDoc
     */
    public function register(): void
    {
        $this->app->singleton(
            Context::class,
            function () {
                return AppContext::background();
            }
        );
    }
}
