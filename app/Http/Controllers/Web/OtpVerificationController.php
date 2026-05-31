<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\OtpVerifyRequest;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OtpVerificationController extends Controller
{
    public function __construct(private readonly OtpService $otpService) {}

    public function show(Request $request): View
    {
        return view('auth.verify-otp', [
            'email' => $request->query('email', old('email')),
            'cooldownSeconds' => OtpService::COOLDOWN_SECONDS,
        ]);
    }

    public function verify(OtpVerifyRequest $request): RedirectResponse
    {
        $user = User::query()->where('email', $request->validated('email'))->firstOrFail();

        $this->otpService->verify($user, $request->validated('otp'));

        return redirect()
            ->route('login')
            ->with('status', 'Email verified successfully. You can now log in.');
    }

    public function resend(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        $user = User::query()->where('email', $data['email'])->firstOrFail();

        if ($user->isActive()) {
            return redirect()->route('login')->with('status', 'Your account is already verified.');
        }

        $this->otpService->send($user);

        return back()->with('status', 'A new OTP has been sent.');
    }
}
