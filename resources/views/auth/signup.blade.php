<x-layouts.auth title="Signup">
    <h1 class="auth-title">Signup</h1>
    <p class="auth-subtitle">Create an account and verify it with an email OTP.</p>

    <form method="POST" action="{{ route('signup.store') }}" class="vstack gap-3">
        @csrf
        <div class="row g-3">
            <div class="col-sm-6">
                <label class="form-label" for="first_name">First Name</label>
                <input id="first_name" name="first_name" value="{{ old('first_name') }}" class="form-control @error('first_name') is-invalid @enderror" required>
                @error('first_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-sm-6">
                <label class="form-label" for="last_name">Last Name</label>
                <input id="last_name" name="last_name" value="{{ old('last_name') }}" class="form-control @error('last_name') is-invalid @enderror" required>
                @error('last_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
        <div>
            <label class="form-label" for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required>
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div>
            <label class="form-label" for="phone_number">Phone Number</label>
            <input id="phone_number" name="phone_number" value="{{ old('phone_number') }}" class="form-control @error('phone_number') is-invalid @enderror" required>
            @error('phone_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="row g-3">
            <div class="col-sm-6">
                <label class="form-label" for="password">Password</label>
                <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-sm-6">
                <label class="form-label" for="password_confirmation">Confirm Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" required>
            </div>
        </div>
        <button type="submit" class="btn btn-primary w-100">Create Account</button>
    </form>

    <p class="auth-switch">Already verified? <a href="{{ route('login') }}">Login</a></p>
</x-layouts.auth>
