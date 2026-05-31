<x-layouts.app title="Edit Profile">
    @php($user = request()->user('api'))
    <div class="page-heading">
        <div>
            <h1>Edit Profile</h1>
            <p>Update your personal details and avatar.</p>
        </div>
    </div>

    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="form-surface">
        @csrf
        @method('PUT')
        <div class="profile-photo-row">
            <img src="{{ $user->profile_photo_path ? asset('storage/'.$user->profile_photo_path) : asset('images/default-avatar.svg') }}" alt="Profile photo">
            <div>
                <label class="form-label" for="profile_photo">Profile Photo</label>
                <input id="profile_photo" type="file" name="profile_photo" class="form-control @error('profile_photo') is-invalid @enderror" accept="image/*">
                @error('profile_photo') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="row g-3 mt-2">
            <div class="col-md-6">
                <label class="form-label" for="first_name">First Name</label>
                <input id="first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}" class="form-control @error('first_name') is-invalid @enderror" required>
                @error('first_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label" for="last_name">Last Name</label>
                <input id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}" class="form-control @error('last_name') is-invalid @enderror" required>
                @error('last_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label" for="email">Email</label>
                <input id="email" value="{{ $user->email }}" class="form-control" disabled>
            </div>
            <div class="col-md-6">
                <label class="form-label" for="phone_number">Phone Number</label>
                <input id="phone_number" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}" class="form-control @error('phone_number') is-invalid @enderror" required>
                @error('phone_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary">Save Profile</button>
        </div>
    </form>
</x-layouts.app>
