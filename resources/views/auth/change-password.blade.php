<x-layouts.auth title="Set New Password">
    <h1 class="auth-title">Set Your Password</h1>
    <p class="auth-subtitle">You are using a temporary password. Please set a new one to continue.</p>

    <form method="POST" action="{{ route('password.change.update') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label" for="password">New Password</label>
            <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" required autofocus>
            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label" for="password_confirmation">Confirm New Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">Set Password</button>
    </form>
</x-layouts.auth>
