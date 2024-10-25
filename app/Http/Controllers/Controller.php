<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

abstract class Controller
{
    
    function register(Request $request) {

        $validator =  Validator::make($request->all(),[
            'name' => 'required|string|max:50',
            'email' => 'required|string|email||max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => '0',
                'massage' => 'fail',
                'data' => $validator ->errors()->all()
            ], 401);      
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json($user, 201);
    }

    function login(Request $request) {

        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'status' => '0',
                'massage' => 'fail',
            ], 401);
        }

        $token = $request->user()->createToken('authToken')->plainTextToken;

        return response()->json(['token' => $token], 200);
    }

    function logout(Request $request) {
        
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }
}
