<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SanitizeInput
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $input = $request->all();

        array_walk_recursive($input, function (&$input, $key) {
            // No limpiar contraseñas
            if (!in_array(strtolower($key), ['password', 'password_confirmation', 'current_password'])) {
                if (is_string($input)) {
                    $input = strip_tags($input);
                }
            }
        });

        $request->merge($input);

        return $next($request);
    }
}
