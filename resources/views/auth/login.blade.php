<x-layouts.auth title="Login">
    <h1 class="auth-title">Login</h1>
    <p class="auth-subtitle">Access the admin dashboard with your verified account.</p>

    <form method="POST" action="{{ route('login.store') }}" class="vstack gap-3">
        @csrf
        <div>
            <label for="email" class="form-label">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required autofocus>
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div>
            <label for="password" class="form-label">Password</label>
            <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="d-flex justify-content-between align-items-center gap-3">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="1" id="remember" name="remember">
                <label class="form-check-label" for="remember">Remember me</label>
            </div>
            <a href="{{ route('password.request') }}" class="link-primary">Forgot password?</a>
        </div>
        <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>

    <p class="auth-switch">New here? <a href="{{ route('signup') }}">Create an account</a></p>
</x-layouts.auth>
