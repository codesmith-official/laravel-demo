<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProfileService
{
    public function update(User $user, array $data, ?UploadedFile $photo = null): User
    {
        if ($photo) {
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }

            $data['profile_photo_path'] = $photo->store('profiles', 'public');
        }

        $data['name'] = trim($data['first_name'].' '.$data['last_name']);

        $user->update($data);

        return $user->refresh();
    }
}
