<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Auth;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            $token = $user->createToken('admin')->accessToken;
            $cookie = cookie('jwt', $token, 3600);
            return response([
                'token' => $token
            ])->withCookie($cookie);
        }

        return response([
            'error' => 'invalid credentials'
        ], Response::HTTP_UNAUTHORIZED);
    }



    public function register(Request $request)
    {
        $user = User::create($request->only('name', 'email') +
            [
                'password' => Hash::make($request->input('password'))
            ]);

        return response($user, Response::HTTP_CREATED);
    }
}
