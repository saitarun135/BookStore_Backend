<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{
   
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }
    public function register(Request $request)
    {
        $this->validate($request, [
            'fullName'=>'required|string|between:3,15',
            'email'=>'required|email|unique:users',
            'password'=>'required|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
            'mobile'=>'required|digits:10',
            ]);
        $user = new User([
            'fullName'=> $request->input('fullName'),
            'email'=> $request->input('email'),
            'password'=> bcrypt($request->input('password')),
            'mobile'=>$request->input('mobile'),      
        ]);
        $user->save();
        return response()->json(['message'=>'Successfully Created user'],201);
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);
        $credentials = $request->only('email', 'password');
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Invalid Credentials'], 401);
            }
        }catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token'],500);
        }      
        return response()->json(['token' => $token], 200);
      
       
    }
}