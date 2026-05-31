<x-layouts.auth title="Verify OTP">
    <h1 class="auth-title">Verify OTP</h1>
    <p class="auth-subtitle">Enter the 6 digit code sent to your email.</p>

    <form method="POST" action="{{ route('otp.verify') }}" class="vstack gap-3">
        @csrf
        <div>
            <label class="form-label" for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ $email }}" class="form-control @error('email') is-invalid @enderror" required>
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div>
            <label class="form-label" for="otp">OTP</label>
            <input id="otp" name="otp" inputmode="numeric" maxlength="6" class="form-control otp-input @error('otp') is-invalid @enderror" required>
            @error('otp') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <button type="submit" class="btn btn-primary w-100">Verify Account</button>
    </form>

    <form method="POST" action="{{ route('otp.resend') }}" class="mt-3">
        @csrf
        <input type="hidden" name="email" value="{{ $email }}">
        <button type="submit" class="btn btn-outline-secondary w-100" data-resend-otp data-cooldown="{{ $cooldownSeconds }}">Resend OTP</button>
    </form>
</x-layouts.auth>
