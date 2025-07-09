<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class TokenSession
{
    public function handle(Request $request, Closure $next)
    {
        // Retrieve the authenticated user's token
        $token = $request->user()?->currentAccessToken();
        // Check if the token exists and has an expiration time
        if (!$token) {
            return response()->json([
                'status' => false,
                'message' => 'Session expired. Please log in again.',
            ]);
        }
        // If token is valid, proceed with the request
        return $next($request);
    }
}
