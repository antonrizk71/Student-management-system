<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\AuthService;
use App\Services\MailService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $authService;
    protected $mailService;

    public function __construct(AuthService $authService,MailService $mailService)
    {
        $this->authService = $authService;
        $this->mailService = $mailService;
    }

    /**
     * Handle user registration.
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $data = $request->validated();

        $result = $this->authService->register($data);

        $userEmail = $result['user']['email'] ?? null;
        $name = $result['user']['name'] ?? null;
        // Send the hello mail
        if ($userEmail) {

            $this->mailService->sendHelloMail($userEmail,$name);
        }


        return response()->json($result, 201);
    }



    /**
     * Handle user login.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        $result = $this->authService->login($credentials);

        return response()->json($result, 200);
    }



    public function logout(Request $request)
    {
        // Revoke the token that was used to authenticate the current request
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }

}
