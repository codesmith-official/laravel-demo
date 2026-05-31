<x-layouts.auth title="Forgot Password">
    <h1 class="auth-title">Forgot Password</h1>
    <p class="auth-subtitle">Enter your email and we will send a reset link.</p>

    <form method="POST" action="{{ route('password.email') }}" class="vstack gap-3">
        @csrf
        <div>
            <label class="form-label" for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required autofocus>
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <button type="submit" class="btn btn-primary w-100">Send Reset Link</button>
    </form>

    <p class="auth-switch"><a href="{{ route('login') }}">Back to login</a></p>
</x-layouts.auth>
