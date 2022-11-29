<?php

namespace A17\TwillRobotsTxt\Http;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use A17\HttpBasicAuth\HttpBasicAuth;
use Illuminate\Http\RedirectResponse;
use A17\TwillRobotsTxt\Support\Facades\TwillRobotsTxt;

class Middleware
{
    public function handle(Request $request, Closure $next): mixed
    {
        $response = TwillRobotsTxt::middleware($request);

        if ($response !== null) {
            return $response;
        }

        return $next($request);
    }
}
