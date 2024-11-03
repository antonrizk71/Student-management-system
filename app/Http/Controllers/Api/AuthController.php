<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Http\Requests\ForgetPasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Models\PasswordReset;
use App\Services\AuthService;
use App\Services\MailService;
use http\Client\Curl\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;


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

    public function forgot(Hasher $hasher , ForgetPasswordRequest $request)
    {
        $user=($query=User::query());
        $user=$user->where($query->qualifyColumn('email'),$request->input('email'))->first();
        if(!$user ||!$user->email)
        {
            return response()->error('no record found' , 'incorrect email address provided',404);

        }
        $resetpasswordtoken = stn_pad(random_int(1,9999),4,'8',STR_PAD_LEFT);
        if(!$userpassreset=PasswordReset::where('email',$user->email)->first()){
            PasswordReset::create([
                'email' => $user->email,
                'token' => $resetpasswordtoken,
            ]);

        }
        else{
            $userpassreset->update([
                'email' => $user->email,
                'token' => $resetpasswordtoken,

            ]);
        }

        //$user->notify(new PasswordResetNotification ($user , $resetpasswordtoken));
        return new jsonResponse(['message' => 'Password reset link sent on your email.']);

    }
    public function reset(ResetPasswordRequest $request)
    {
        $attributes = $request->validated();
        $user=User::where ('email',$attributes['email'])->first();
        if(!$user){
            return response()->error('no record found','incorrect email address provided',404);
        }
        $resetRequest = PasswordReset::where('email', $user->email)->first();
        if(!$resetRequest){
            return response()->error('an error occured. please try again','token mismatched',400);
        }
        $user->fill([
            'password' => Hash::make($attributes['password']),
        ]);
        $user->save();
        $user->tokens()->delete();
        $resetRequest->delete();
        $token=$user->createToken('authtoken')->plainTextToken;
        $loginResponse=[
            'user'=>UserResponse::make($user),
            'token'=>$token
        ];
        return response()->success($loginResponse , 'Password reset successfully',201);

    }


}
