<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Auth;

class SanctumAuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->bearerToken()) {
            return response()->json(['status' => false, 'message' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        $token = PersonalAccessToken::findToken($request->bearerToken());

        if (!$token || !$token->tokenable) {
            return response()->json(['status' => false, 'message' => 'Invalid Token'], Response::HTTP_UNAUTHORIZED);
        }
        
        return $next($request);
    }
}
