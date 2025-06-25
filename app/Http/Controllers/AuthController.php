<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

use function Pest\Laravel\json;

class AuthController extends Controller
{
    /**
     * Register a new user
     * @param Request $request
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create($validated);

        $token = $user->createToken($request->name)->plainTextToken;

            return response()->json([
                'message' => 'User created successfully',
                'user' => $user,
                'token' => $token
            ]);
        }
}
