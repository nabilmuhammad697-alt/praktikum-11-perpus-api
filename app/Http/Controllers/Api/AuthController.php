<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
{
    $user = User::create($request->validated()); // password di-hash oleh cast
    $token = $user->createToken('api-token')->plainTextToken;

    return response()->json([
        'message' => 'Registrasi berhasil',
        'token' => $token,
        'token_type' => 'Bearer',
        'user' => new UserResource($user),
    ], 201);
}

public function login(LoginRequest $request): JsonResponse
{
    $user = User::where('email', $request->input('email'))->first();

    if (! $user || ! Hash::check($request->input('password'), $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['Email atau password salah.'],
        ]);
    }

    $token = $user->createToken($request->input('device_name') ?: 'api-token')->plainTextToken;

    return response()->json([
        'message' => 'Login berhasil',
        'token' => $token,
        'token_type' => 'Bearer',
        'user' => new UserResource($user),
    ]);
}

public function me(Request $request): UserResource
{
    return new UserResource($request->user());
}

public function logout(Request $request): JsonResponse
{
    $request->user()->currentAccessToken()->delete();

    return response()->json(['message' => 'Logout berhasil']);
}
}
