<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function __construct(protected AuthService $authService)
    {
    }

    public function register(RegisterRequest $request)
    {
        $payload = $request->requestDataObject();

        $user = $this->authService->register($payload);

        event(new Registered($user));

        return $this->authService->loginUser($user);
    }

    public function me(Request $request)
    {
        return response()->json(
            new UserResource($request->user())
        );
    }

    public function createAuthToken(LoginRequest $request)
    {
        $request->authenticate();

        $user = $request->user();

        return $this->authService->loginUser($user);
    }

    public function logout(Request $request) {
        $user = $request->user();

        $user->tokens()->delete();
    }
}
