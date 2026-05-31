<?php

namespace App\Http\Middleware;

use App\Services\AuthService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UsePassportTokenCookie
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->cookie(AuthService::TOKEN_COOKIE);

        if (! $request->bearerToken() && $token) {
            $authorization = 'Bearer '.$token;

            $request->headers->set('Authorization', $authorization);
            $request->server->set('HTTP_AUTHORIZATION', $authorization);
        }

        return $next($request);
    }
}
