<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTokenExpiry
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $token = $user->currentAccessToken();

        // Check if the token has an expiry date and if it's expired
        if ($token && $token->expires_at && $token->expires_at->isPast()) {
            // Token is expired, revoke it
            $token->delete();

            return response()->json(['message' => 'Your token has expired.'], 401);
        }

        return $next($request);
    }
}
