<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;
use JWTFactory;
use JWTAuth;
use App\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password'=> 'required'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
        $credentials = $request->only('email', 'password');
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return $this->response('error', 'incorrect login details', '');    
            }
        } catch (JWTException $e) {
            return $this->response('error', 'could_not_create_token', '');
            
            // return response()->json(['error' => 'could_not_create_token'], 500);
        }
        return $this->response('success', 'login successful', ['token' => $token]);
    }


    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:users',
            'name' => 'required',
            'password'=> 'required'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => bcrypt($request->get('password')),
        ]);
         
        $token = JWTAuth::fromUser($user);
        
        return $this->response('success', 'registration successful', 
                                ['token' => $token, 'user' => $user]);
    }

}
