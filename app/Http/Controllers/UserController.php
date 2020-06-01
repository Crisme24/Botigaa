<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //Register
    public function register(Request $request)
    {
        try {
            //code...
            $body = $request->all();
            $body['role'] = 'user';
            $body['password'] = Hash::make($body['password']);
            $user = User::create($body);
            return response($user, 201);
        } catch (\Exception $e) {
            //throw $th;
            return response([
                'message' => 'Trere was an error trying to register the user',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            //code...
            $credentials = $request->only(['email', 'password']);
            if(!Auth::attempt($credentials)){
                return response([
                    'message' => 'Wrong credentials'
                ], 400);
            }
            $user = Auth::user();
            $token = $user->createToken('authToken')->accessToken;
            $user->token=$token;
            return response([
                'user'=>$user
            ]);
        } catch (\Exception $e) {
            //throw $th;
            return response([
                'message' => 'There was an error trying to login the user',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function logout(){
        try {
            //code...
            //Auth::user()->token()->delete();
            Auth::user()->token()->revoke();
            return response([
                'message' => 'User successfully disconected'
            ]);
        } catch (\Exception $e) {
            //throw $th;
            return response([
                'message' => 'There was an error trying to login the user',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
