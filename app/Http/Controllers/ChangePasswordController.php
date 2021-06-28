<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UpdatePasswordRequest;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\PasswordReset;
use Illuminate\Support\Facades\Validator as FacadesValidator;

class ChangePasswordController extends Controller
{
    public function resetPassword(Request $request)
    {
        $validate = FacadesValidator::make($request->all(), [
            'new_password' => 'min:6|required|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
            'confirm_password' => 'required|same:new_password'
        ]);

        if ($validate->fails()) {
            return response()->json(['message' => "Password doesn't match"]);
        }
        $passwordReset = PasswordReset::where([
            ['token', $request->bearerToken()]])->first();
        
            if (!$passwordReset) {           
            return response()->json([ 'message' => 'This token is invalid'], 201);
            }
        $user = User::where('email', $passwordReset->email)->first();
        
        if (!$user) {
            return response()->json(['message' => "we can't find the user with that e-mail address"], 201);
        } 
        else {
            $user->password = bcrypt($request->new_password);
            $user->save();
            $passwordReset->delete();
            return response()->json(['data'=>'Password has been updated.'],Response::HTTP_CREATED);
        }
    }
}
