<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use PragmaRX\Google2FALaravel\Support\Authenticator;

class TwoFA
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $authenticator = app(Authenticator::class)->boot($request);

        if (skip_2fa() || $authenticator->isAuthenticated()) {
            return $next($request);
        }

        return $authenticator->makeRequestOneTimePasswordResponse();
    }
}
