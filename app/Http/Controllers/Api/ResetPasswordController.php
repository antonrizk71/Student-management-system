<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ResetPasswordController extends Controller
{
public function reset(Request $request)
{
// Validate the request
$request->validate([
'email' => 'required|email',
'token' => 'required',
'password' => 'required|confirmed|min:8',
]);

// Attempt to reset the password
$response = Password::broker()->reset(
$request->only('email', 'password', 'token'),
function ($user, $password) {
$user->password = bcrypt($password);
$user->save();
}
);

// Return the appropriate response
return $response == Password::PASSWORD_RESET
? response()->json(['message' => trans($response)], 200)
: response()->json(['message' => trans($response)], 400);
}
}
