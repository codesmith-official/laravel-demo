<p>Hello {{ $user->first_name }},</p>
<p>Your signup verification OTP is:</p>
<h2>{{ $otp }}</h2>
<p>This code expires at {{ $expiresAt->format('Y-m-d H:i:s') }} UTC.</p>
<p>If you did not request this account, you can ignore this email.</p>
