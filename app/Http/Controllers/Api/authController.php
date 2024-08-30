<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class authController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|string|min:8|max:255'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ]);

        if ($user) {
            return response()->json([
                'message' => 'User registered successfully',
                'data' => $user
            ], 201);
        } else {
             return response()->json([
                'message' => 'Something went wrong',
            ], 500);
        }
    }

    public function login(Request $request)
    {
           $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:8|max:255'
        ]);

         $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    $token = $user->createToken($user->name.'auth-token')->plainTextToken;

    return response()->json([
         'message' => 'Login successfully',
        'token' => $token
    ],
     200);
    }

    public function logout(Request $request)
    {
      $user = User::where('id', $request->user()->id)->first();
      $user->tokens()->delete();
      
      if ($user) {
            return response()->json([
                'message' => 'Logout successful',
            ], 201);
        } else {
             return response()->json([
                'message' => 'Something went wrong',
            ], 500);
        }

    }

}
