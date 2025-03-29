<?php

namespace App\Http\Middleware;

use Closure;

class CustomPostSize
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $maxSize = 20 * 1024 * 1024; // 20 MB en bytes

        if ($request->server('CONTENT_LENGTH') > $maxSize) {
            return response('Request Entity Too Large', 413);
        }

        return $next($request);
    }
}
