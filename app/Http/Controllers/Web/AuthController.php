<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\SignupRequest;
use App\Services\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function __construct(private readonly AuthService $authService) {}

    public function login(): View
    {
        return view('auth.login');
    }

    public function signup(): View
    {
        return view('auth.signup');
    }

    public function storeSignup(SignupRequest $request): RedirectResponse
    {
        $user = $this->authService->register($request->validated());

        return redirect()
            ->route('otp.show', ['email' => $user->email])
            ->with('status', 'We sent a 6 digit OTP to your email address.');
    }

    public function storeLogin(LoginRequest $request): RedirectResponse
    {
        $token = $this->authService->login([
            ...$request->validated(),
            'remember' => $request->boolean('remember'),
        ]);

        $minutes = max(1, now()->diffInMinutes($token['expires_at']));

        return redirect()
            ->route('dashboard')
            ->withCookie(cookie(
                AuthService::TOKEN_COOKIE,
                $token['token'],
                $minutes,
                '/',
                config('session.domain'),
                $request->isSecure(),
                true,
                false,
                'lax'
            ));
    }

    public function logout(Request $request): RedirectResponse
    {
        $this->authService->logout($request);

        return redirect()
            ->route('login')
            ->with('status', 'You have been logged out.')
            ->withCookie(cookie()->forget(AuthService::TOKEN_COOKIE));
    }
}
