<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255', //nama
            'email' => 'required|string|email|max:255', //email or nik
            'dept_code' => 'required|string|max:255', //kode departemen
            'password' => 'required|string|min:6', //password
        ]);


        // Check if the email is already used
        if (User::where('email', $validatedData['email'])->exists()) {
            return response()->json(['error' => 'Email already in use'], 422);
        }

        $message = 'User Created Successfully';

        // Create a new user
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'dept_code' =>$validatedData['dept_code'],
            'password' => Hash::make($validatedData['password']),
        ]);

        return response()->json(['message' => $message, 'user' => $user], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('AppName')->plainTextToken; #IGNORE NOT ERROR

            return response()->json(['user' => $user, 'token' => $token], 200);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }
}
