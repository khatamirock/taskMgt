<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //
    public function register(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'sometimes|string|in:admin,member',
        ]);


        

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role ?? 'member',
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
            'token' => $token,
            'token_type' => 'Bearer',
        ], 201);


    }
    public function login(Request $request)
    {
        // 1. Validate the input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 2. Attempt to log in
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        
        // 3. Get the authenticated user
        $user = Auth::user(); 

        // 4. Generate token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status'=>"OK Bro",
            'token' => $token,
            'user' => $user // Optional: useful for the frontend to know the role/name
        ]);
    }

    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
            return response()->json(['message' => 'Logged out']);
    }


 


    


}
