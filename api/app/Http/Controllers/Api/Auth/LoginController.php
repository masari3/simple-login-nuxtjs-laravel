<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login (LoginRequest $request)
    {
        if ( ! Auth::attempt($request->only('fullname','username','email','password')) )
        {
            return response()->json([
                'status'    => false,
                'message'   => 'Unauthorized!'
            ], 401);
        }

        $data = User::where('email', $request->email)->orWhere('username', $request->username)->orWhere('fullname', $request->fullname)->firstOrFail();
        $token = $data->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message'   => 'Welcome back!',
            'access_token'  => $token,
            'token_type'    => 'Bearer'
        ], 200);
    }

    public function logout ()
    {
        Auth::user()->tokens()->delete();
        return response()->json([
            'status'    => true,
            'message'   => 'Bye!',
        ]);
    }

    public function profile ($id)
    {
        $user = Auth::user()->find($id);
        return response()->json([
            'status'    => true,
            'detail'    => $user
        ]);
    }
}
