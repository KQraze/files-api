<?php

namespace App\Http\Requests;

use App\Http\Requests\ApiRequest;
use Illuminate\Validation\Rules\Password;

class RegistrationRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', Password::min(3)->mixedCase()->numbers()],
        ];
    }
}
