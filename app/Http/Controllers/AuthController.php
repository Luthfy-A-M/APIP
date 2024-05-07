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
        try {

            if ($request->getMethod() !== 'POST') {
                // If the request method is not POST, return a response indicating that POST method is required
                return response()->json(['error' => 'POST method is required'], 405);
            }
            $validatedData = $request->validate([
                'name' => 'required|string|max:255', // Name
                'email' => 'required|string|email|max:255', // Email or nik
                'dept_code' => 'required|string|max:255', // Department code
                'password' => 'required|string|min:6', // Password
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
                'dept_code' => $validatedData['dept_code'],
                'password' => Hash::make($validatedData['password']),
            ]);

            return response()->json(['message' => $message, 'user' => $user], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            if ($request->getMethod() !== 'POST') {
                // If the request method is not POST, return a response indicating that POST method is required
                return response()->json(['error' => 'POST method is required'], 405);
            }
            $credentials = $request->only('email', 'password');

            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                $token = $user->createToken('AppName')->plainTextToken;

                return response()->json(['user' => $user, 'token' => $token], 200);
            }

            return response()->json(['error' => 'Unauthorized'], 401);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
