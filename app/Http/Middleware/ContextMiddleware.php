<?php

namespace App\Http\Middleware;

use App\Libraries\Context\AppContext;
use Illuminate\Http\Request;

class ContextMiddleware
{
    public function handle(Request $request, \Closure $next): mixed
    {
        return $next(
            AppContext::withRequest($request, AppContext::background())
        );
    }
}
