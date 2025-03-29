<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('login');
        }
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string[]  ...$guards
     * @return mixed
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle($request, Closure $next, ...$guards)
    {
	$guard = $guards[0] ?? 'web';

        // Check if the user is authenticated with the specified guard
        if (Auth::guard($guard)->guest()) {
            // Handle API or AJAX requests
            if ($request->is('api/*') || $request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            }
			
			

            // Redirect based on the guard
            if ($guard === 'clientes') {
			    session(['url_intended_custom' => $request->url()]);
               // return redirect()->route('personas.login.form');
            } else {
				//session(['url_intended_custom' => $request->url()]);
                return redirect()->route('login');
            }
        }

        return $next($request);


       /* if (Auth::guard($guards)->guest()) {
            if ($request->is('api/*') || $request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            }
	    if ($guard === 'clientes') {
                return redirect()->route('personas.login.form');
            }

            return redirect()->guest('login');
        }
        $this->authenticate($request, $guards);

        return $next($request);*/
    }
}
