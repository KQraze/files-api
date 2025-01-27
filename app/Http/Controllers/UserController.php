<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiValidateException;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\RegistrationRequest;
use App\Models\UsersFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function authorization(Request $request): array
    {
        $user = User::where(['email' => $request->email])->first();

        if ($user && Hash::check($request->password, $user->password)) {
            return [
                'code' => 200,
                'success' => true,
                'message' => 'Success',
                'token' => $user->createToken(Str::random(5))->plainTextToken
            ];
        }

        throw new ApiValidateException(401, false, 'Authorization failed');
    }

    public function registration(RegistrationRequest $request): array
    {
        return [
            'code' => 201,
            'success' => true,
            'message' => 'Success',
            'token' => User::create($request->except('password')+['password' => Hash::make($request->get('password'))])->createToken(Str::random(5))->plainTextToken,
        ];
    }

    public function logout(Request $request): void
    {
        $request->user()->currentAccessToken()->delete();
    }
}
