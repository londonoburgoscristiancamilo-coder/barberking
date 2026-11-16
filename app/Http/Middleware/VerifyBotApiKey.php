<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyBotApiKey
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = config('services.barberking_bot.key');
        $providedKey = $request->header('X-Bot-Api-Key');

        if (!is_string($apiKey) || $apiKey === '' ||
            !is_string($providedKey) || !hash_equals($apiKey, $providedKey)) {
            return response()->json([
                'success' => false,
                'mensaje' => 'No autorizado'
            ], 401);
        }

        return $next($request);
    }
}