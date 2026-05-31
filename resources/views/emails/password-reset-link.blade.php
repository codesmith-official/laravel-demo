<p>Hello {{ $user->first_name }},</p>
<p>Use the link below to reset your password:</p>
<p><a href="{{ $resetUrl }}">{{ $resetUrl }}</a></p>
<p>This password reset link expires according to the application security policy.</p>
