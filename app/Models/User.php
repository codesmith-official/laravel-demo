<?php

namespace App\Models;

use Database\Factories\UserFactory;
use App\Services\MailService;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

#[Fillable([
    'first_name',
    'last_name',
    'name',
    'email',
    'phone_number',
    'password',
    'status',
    'profile_photo_path',
    'email_verified_at',
    'last_login_at',
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements CanResetPasswordContract
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}") ?: $this->name;
    }

    public function isActive(): bool
    {
        return $this->status === 'active' && $this->email_verified_at !== null;
    }

    public function sendPasswordResetNotification($token): void
    {
        app(MailService::class)->send($this->email, new \App\Mail\PasswordResetLinkMail($this, $token));
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
