<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Cache;

class PreventDuplicateSubmits
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            $key = 'prevent_duplicate_' . md5($request->ip() . $request->url() . json_encode($request->except(['_token'])));
            
            // Bloqueo por 3 segundos para evitar doble click
            if (!Cache::add($key, true, 3)) {
                return redirect()->back()->with('error', 'Por favor, no envíes el formulario más de una vez. Tu petición ya está siendo procesada.');
            }
        }

        return $next($request);
    }
}
