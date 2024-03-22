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
        if (auth()->check()) {
            $user = auth()->user();

            if ($user->{'2fa'} === null) {
                return redirect()->route('user.profile');
            } else {
                // User does not have 2FA set up, redirect to profile setup
                $authenticator = app(Authenticator::class)->boot($request);

                if (skip_2fa() || $authenticator->isAuthenticated()) {
                    return $next($request);
                }
                return $authenticator->makeRequestOneTimePasswordResponse();
            }
        } else {
            return redirect()->route('login');
        }
    }
}
