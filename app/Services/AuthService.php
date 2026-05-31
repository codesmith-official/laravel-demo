<?php

namespace App\Services;

use App\Mail\PasswordResetConfirmationMail;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public const TOKEN_COOKIE = 'passport_token';

    public function __construct(
        private readonly UserRepository $users,
        private readonly OtpService $otpService,
        private readonly MailService $mail,
    ) {}

    public function register(array $data): User
    {
        return DB::transaction(function () use ($data): User {
            $fullName = trim($data['first_name'].' '.$data['last_name']);

            $user = User::query()->create([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'name' => $fullName,
                'email' => $data['email'],
                'phone_number' => $data['phone_number'] ?? null,
                'password' => $data['password'],
                'status' => 'inactive',
            ]);

            $this->otpService->send($user);

            return $user;
        });
    }

    public function login(array $credentials): array
    {
        $user = $this->users->findByEmail($credentials['email']);

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => 'The provided credentials are incorrect.',
            ]);
        }

        if (! $user->isActive()) {
            throw ValidationException::withMessages([
                'email' => 'Your account is inactive. Please verify your email OTP first.',
            ]);
        }

        $user->forceFill(['last_login_at' => now()])->save();

        $tokenResult = $user->createToken('blade-admin');
        $expiresAt = ($credentials['remember'] ?? false) ? now()->addDays(30) : now()->addHours(8);
        $tokenResult->token->forceFill(['expires_at' => $expiresAt])->save();

        return [
            'token' => $tokenResult->accessToken,
            'expires_at' => $expiresAt,
        ];
    }

    public function logout(Request $request): void
    {
        $token = $request->user('api')?->token();

        if ($token) {
            $token->revoke();
        }
    }

    public function sendPasswordResetLink(array $data): string
    {
        return Password::sendResetLink(['email' => $data['email']]);
    }

    public function resetForgottenPassword(array $data): string
    {
        return Password::reset(
            $data,
            function (User $user, string $password): void {
                $user->forceFill([
                    'password' => $password,
                    'remember_token' => Str::random(60),
                ])->save();

                $user->tokens()->update(['revoked' => true]);

                event(new PasswordReset($user));

                $this->mail->send($user->email, new PasswordResetConfirmationMail($user));
            }
        );
    }

    public function resetAuthenticatedPassword(User $user, array $data): void
    {
        if (! Hash::check($data['old_password'], $user->password)) {
            throw ValidationException::withMessages([
                'old_password' => 'The old password is incorrect.',
            ]);
        }

        DB::transaction(function () use ($user, $data): void {
            $user->forceFill([
                'password' => $data['password'],
                'remember_token' => Str::random(60),
            ])->save();

            $user->tokens()->update(['revoked' => true]);
        });

        $this->mail->send($user->email, new PasswordResetConfirmationMail($user));
    }
}
