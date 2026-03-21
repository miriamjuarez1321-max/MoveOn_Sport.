<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BlockDirectAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $referer = $request->headers->get('referer');
        
        if (!$referer || parse_url($referer, PHP_URL_HOST) !== $request->getHost()) {
            return redirect('/')->with('error', 'No tienes permitido acceder a esta sección directamente.');
        }

        return $next($request);
    }
}
