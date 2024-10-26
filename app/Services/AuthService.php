<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;

class AuthService
{
    /**
     * Register a new user and return the auth token.
     */
    public function register(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        // Generate token
        $token = $user->createToken('auth_token')->plainTextToken;
        // Update the token's expiration time
        $user->tokens()->latest()->first()->update([
            'expires_at' => now()->addDays(7),
        ]);

        return [
            'user' => $user,
            'token' => $token,
            'token_type' => 'Bearer',
        ];
    }

    /**
     * Login a user and return the auth token.
     */
    public function login(array $credentials)
    {
        if (!Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        // Update the token's expiration time
        $user->tokens()->latest()->first()->update([
            'expires_at' => now()->addDays(7),
        ]);

        return [
            'user' => $user,
            'token' => $token,
            'token_type' => 'Bearer',
        ];
    }



}
