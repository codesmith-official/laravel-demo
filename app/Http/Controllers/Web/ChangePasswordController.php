<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\ChangePasswordRequest;
use App\Services\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChangePasswordController extends Controller
{
    public function show(): View
    {
        return view('auth.change-password');
    }

    public function update(ChangePasswordRequest $request): RedirectResponse
    {
        $user = $request->user('api');

        $user->forceFill([
            'password' => $request->validated('password'),
            'is_temp_password' => false,
        ])->save();

        $user->tokens()->update(['revoked' => true]);

        return redirect()
            ->route('login')
            ->with('status', 'Password updated. Please log in with your new password.')
            ->withCookie(cookie()->forget(AuthService::TOKEN_COOKIE));
    }
}
