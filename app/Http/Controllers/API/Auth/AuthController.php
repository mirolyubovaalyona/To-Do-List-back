<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    function register(Request $request) {

        $validator =  Validator::make($request->all(),[
            'name' => 'required|string|max:50',
            'email' => 'required|string|email||max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->sendEmailVerificationNotification();

        return response()->json(['message' => 'User registered. Please check your email to verify your account.'], 201);
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

        session()->regenerate();

        $token = $request->user()->createToken('authToken')->plainTextToken;

        return response()->json(['token' => $token], 200);
    }

    function logout(Request $request) {
        
        auth()->guard('web')->logout();

        $request->user()->tokens()->delete();

        session()->invalidate();

        session()->regenerateToken();

        return response()->json(['message' => 'Logged out successfully']);
    }
}
