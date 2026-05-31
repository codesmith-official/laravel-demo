<x-layouts.app title="Reset Password">
    <div class="page-heading">
        <div>
            <h1>Reset Password</h1>
            <p>All active API tokens will be invalidated after this change.</p>
        </div>
    </div>

    <form method="POST" action="{{ route('profile.password.update') }}" class="form-surface">
        @csrf
        @method('PUT')
        <div class="row g-3">
            <div class="col-12">
                <label class="form-label" for="old_password">Old Password</label>
                <input id="old_password" type="password" name="old_password" class="form-control @error('old_password') is-invalid @enderror" required>
                @error('old_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label" for="password">New Password</label>
                <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label" for="password_confirmation">Confirm Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" required>
            </div>
        </div>
        <div class="mt-4">
            <button type="submit" class="btn btn-primary">Reset Password</button>
        </div>
    </form>
</x-layouts.app>
