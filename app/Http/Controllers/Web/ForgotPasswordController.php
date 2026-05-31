<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Services\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class ForgotPasswordController extends Controller
{
    public function __construct(private readonly AuthService $authService) {}

    public function show(): View
    {
        return view('auth.forgot-password');
    }

    public function send(ForgotPasswordRequest $request): RedirectResponse
    {
        $status = $this->authService->sendPasswordResetLink($request->validated());

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('status', __($status));
        }

        return back()->withErrors(['email' => __($status)]);
    }
}
