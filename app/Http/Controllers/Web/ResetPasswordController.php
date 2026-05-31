<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ResetForgottenPasswordRequest;
use App\Http\Requests\Profile\PasswordUpdateRequest;
use App\Services\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class ResetPasswordController extends Controller
{
    public function __construct(private readonly AuthService $authService) {}

    public function showForgotten(Request $request, string $token): View
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    public function resetForgotten(ResetForgottenPasswordRequest $request): RedirectResponse
    {
        $status = $this->authService->resetForgottenPassword($request->validated());

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('status', __($status));
        }

        return back()->withErrors(['email' => __($status)]);
    }

    public function showAuthenticated(): View
    {
        return view('profile.reset-password');
    }

    public function resetAuthenticated(PasswordUpdateRequest $request): RedirectResponse
    {
        $this->authService->resetAuthenticatedPassword($request->user('api'), $request->validated());

        return redirect()
            ->route('login')
            ->with('status', 'Password reset successfully. Please log in again.')
            ->withCookie(cookie()->forget(AuthService::TOKEN_COOKIE));
    }
}
