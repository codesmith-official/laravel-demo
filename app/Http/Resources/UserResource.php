<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'last_login_at' => $this->last_login_at?->format('Y-m-d H:i:s') ?? 'Never',
            'status' => $this->status,
            'profile_photo_url' => $this->profile_photo_path ? asset('storage/'.$this->profile_photo_path) : asset('images/default-avatar.svg'),
        ];
    }
}
