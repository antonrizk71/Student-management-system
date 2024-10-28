<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    public function sendResetLinkEmail(Request $request)
    {
        // Validate the request to ensure the email is provided and is valid
        $request->validate(['email' => 'required|email']);

        // Attempt to send the reset link
        $response = Password::broker()->sendResetLink($request->only('email'));

        // Return the appropriate response
        return $response == Password::RESET_LINK_SENT
            ? response()->json(['message' => trans($response)], 200)
            : response()->json(['message' => trans($response)], 400);
    }
}
