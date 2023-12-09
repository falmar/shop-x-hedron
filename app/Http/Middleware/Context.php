<?php

namespace App\Http\Middleware;

use App\Libraries\Context\AppContext;
use Illuminate\Http\Request;

class Context
{
    public function handle(Request $request, \Closure $next)
    {
        return $next(
            AppContext::withRequest($request, AppContext::background())
        );
    }
}
