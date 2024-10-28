<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\SendMailController;
use App\Services\MailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');


//This  routes  only for testing
Route::middleware(['auth:sanctum','checkRole:SuperAdmin'])->get('test', function () {
    return response()->json(['message' => 'Success']);
});

Route::get('/test-mail', function (MailService $mailService) {
    $mailService->sendHelloMail('antonrizk71@gmail.com','anton');
    return 'Test email sent!';
});
///////////////////
