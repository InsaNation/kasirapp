<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RemoveTrailingSlash
{
    public function handle(Request $request, Closure $next)
    {
        $uri = $request->getRequestUri();

        if (strlen($uri) > 1 && $uri[-1] === '/') {
            return redirect(rtrim($uri, '/'), 301);
        }

        return $next($request);
    }
}
