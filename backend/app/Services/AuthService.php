<?php

namespace App\Services;

use App\Dtos\RegisterUserDto;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function register(RegisterUserDto $payload): User
    {
        $user = User::create([
            'name' => $payload->name,
            'email' => $payload->email,
            'password' => Hash::make($payload->password),
        ]);

        event(new Registered($user));

        return $user;
    }


    public function loginUser(User $user): \Illuminate\Http\JsonResponse
    {
        $auth_token = $this->generateAuthToken($user);

        return response()->json([
            'access_token' => $auth_token,
            'user' => UserResource::make($user),
        ]);
    }


    public function generateAuthToken(User $user): string
    {
        return $user->createToken('auth_token')->plainTextToken;
    }
}
