<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class SignupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email:rfc,dns', 'max:255', 'unique:users,email'],
            'phone_number' => ['required', 'string', 'max:30'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ];
    }
}
