<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ApiAuthController extends Controller
{
    public function register(Request $request)
    {

        $validatedData = $request->validate([

            'name' => 'required|max:55',
            'email' => 'email|required',
            'password' => 'required|confirmed',
        ]);
        $validatedData['password']=bcrypt($validatedData['password']);
        $user = User::create($validatedData);

        $accessToken = $user->createToken('authToken')->accessToken;

        return response(['user' => $user, 'accessToken' => $accessToken]);

    }
    public function login(Request $request)
   {
        $loginData = $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);
      // dd($loginData);
        if(!auth()->attempt($loginData)) {
            return response(['message'=>'Invalid credentials']);
        }

        $accessToken = auth()->user()->createToken('authToken')->accessToken;

        return response(['user' => auth()->user(), 'access_token' => $accessToken]);

   }
}
