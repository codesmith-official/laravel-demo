<?php

namespace App\Http\Middleware;

use App\Services\AuthService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsurePassportUser
{
    public function handle(Request $request, Closure $next): Response
    {
        Auth::shouldUse('api');

        $user = Auth::guard('api')->user();

        if (! $user) {
            return redirect()->route('login')->withCookie(cookie()->forget(AuthService::TOKEN_COOKIE));
        }

        return $next($request);
    }
}
