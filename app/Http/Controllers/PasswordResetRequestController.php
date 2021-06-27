<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use App\Mail\SendMailreset;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Models\PasswordReset;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Notifications\ResetPasswordNotification;


class PasswordResetRequestController extends Controller
{
    public function forgotPassword(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['message' => 'Email doesn\'t found on our database'], 401);
        }
        $passwordReset = PasswordReset::updateOrCreate(
            ['email' => $user->email],
            [
                'email' => $user->email,
                'token' => JWTAuth::fromUser($user)
            ]
        );
        if ($user && $passwordReset) {
            $user->notify(new ResetPasswordNotification($passwordReset->token));
        }
        return response()->json(['message' => 'Reset link is send successfully, please check your inbox.']);
    }
}
    
