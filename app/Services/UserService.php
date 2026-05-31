<?php

namespace App\Services;

use App\Mail\AdminUserCreatedMail;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class UserService
{
    public function __construct(private readonly MailService $mail) {}

    public function create(array $data): User
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
                'status' => $data['status'],
                'email_verified_at' => now(),
            ]);

            $this->mail->send($user->email, new AdminUserCreatedMail($user, $data['password']));

            return $user;
        });
    }

    public function update(User $user, array $data): User
    {
        $fullName = trim($data['first_name'].' '.$data['last_name']);

        $user->update([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'name' => $fullName,
            'phone_number' => $data['phone_number'] ?? null,
            'status' => $data['status'],
        ]);

        return $user->refresh();
    }

    public function delete(User $user, ?User $actor = null): void
    {
        if ($actor && $actor->is($user)) {
            throw ValidationException::withMessages([
                'user' => 'You cannot delete your own account while signed in.',
            ]);
        }

        $user->delete();
    }
}
