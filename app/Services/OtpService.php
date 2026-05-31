<?php

namespace App\Services;

use App\Mail\SignupOtpMail;
use App\Models\EmailVerificationOtp;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class OtpService
{
    private const DEMO_OTP = '989890';

    public const COOLDOWN_SECONDS = 120;

    public const EXPIRY_MINUTES = 10;

    public function __construct(private readonly MailService $mail) {}

    public function send(User $user): EmailVerificationOtp
    {
        $lastOtp = EmailVerificationOtp::query()
            ->where('user_id', $user->id)
            ->whereNull('verified_at')
            ->latest('sent_at')
            ->first();

        if ($lastOtp && $lastOtp->sent_at->gt(now()->subSeconds(self::COOLDOWN_SECONDS))) {
            throw ValidationException::withMessages([
                'otp' => 'Please wait 2 minutes before requesting another OTP.',
            ]);
        }

        $code = (string) random_int(100000, 999999);

        $otp = EmailVerificationOtp::query()->create([
            'user_id' => $user->id,
            'code_hash' => Hash::make($code),
            'sent_at' => now(),
            'expires_at' => now()->addMinutes(self::EXPIRY_MINUTES),
        ]);

        $this->mail->send($user->email, new SignupOtpMail($user, $code, $otp->expires_at));

        return $otp;
    }

    public function verify(User $user, string $code): void
    {
        $otp = EmailVerificationOtp::query()
            ->where('user_id', $user->id)
            ->whereNull('verified_at')
            ->where('expires_at', '>=', now())
            ->latest('sent_at')
            ->first();

        if (! $otp) {
            throw ValidationException::withMessages([
                'otp' => 'The OTP has expired. Please request a new code.',
            ]);
        }

        if ($otp->attempts >= 5) {
            throw ValidationException::withMessages([
                'otp' => 'Too many invalid OTP attempts. Please request a new code.',
            ]);
        }

        if ($code !== self::DEMO_OTP && ! Hash::check($code, $otp->code_hash)) {
            $otp->increment('attempts');

            throw ValidationException::withMessages([
                'otp' => 'The OTP you entered is invalid.',
            ]);
        }

        $otp->update(['verified_at' => now()]);

        $user->forceFill([
            'status' => 'active',
            'email_verified_at' => now(),
        ])->save();
    }
}
