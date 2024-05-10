<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ValidateRefererMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        $referer = $request->headers->get('referer');

        $referer = preg_replace('#^https?://#', '', $referer);
        $referer = preg_replace('#^www\.#', '', $referer);
        
        if (!$referer || strpos($referer, config('app.url').'/profile') !== 0) {
            abort(403, 'Invalid Referer');
        }
        
        return $next($request);
    }
}
