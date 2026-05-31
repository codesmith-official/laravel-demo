<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\ProfileUpdateRequest;
use App\Services\ProfileService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function __construct(private readonly ProfileService $profiles) {}

    public function edit(): View
    {
        return view('profile.edit');
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $this->profiles->update(
            $request->user('api'),
            $request->safe()->except('profile_photo'),
            $request->file('profile_photo')
        );

        return back()->with('status', 'Profile updated successfully.');
    }
}
