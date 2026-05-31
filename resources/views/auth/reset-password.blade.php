<x-layouts.auth title="Reset Password">
    <h1 class="auth-title">Reset Password</h1>
    <p class="auth-subtitle">Choose a new password for your account.</p>

    <form method="POST" action="{{ route('password.update') }}" class="vstack gap-3">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <div>
            <label class="form-label" for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email', $email) }}" class="form-control @error('email') is-invalid @enderror" required>
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div>
            <label class="form-label" for="password">New Password</label>
            <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div>
            <label class="form-label" for="password_confirmation">Confirm Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Reset Password</button>
    </form>
</x-layouts.auth>
