<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Register a new user
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
            ]);

            $user = User::create($validated);
            $token = $user->createToken($request->name)->plainTextToken;
            
            DB::commit();

            return response()->json([
                'message' => 'User created successfully',
                'user' => $user,
                'token' => $token
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack(); 
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    /**
     * Login a user
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'email' => 'required|string|email|max:255|exists:users',
                'password' => 'required|string',
            ]);
    
            $user = User::where('email', $validated['email'])->first();
    
            if (!$user) {
                return response()->json([
                    'message' => 'Authentication failed',
                    'errors' => [
                        'email' => ['User not found']
                    ]
                ], 404);
            }

            if (!Hash::check($validated['password'], $user->password)) {
                return response()->json([
                    'message' => 'Authentication failed',
                    'errors' => [
                        'password' => ['Incorrect password']
                    ]
                ], 401);
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'Login successful',
                'status' => true,
                'token' => $token,
                'user' => $user
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    /**
     * Logout a user
     * @param Request $request\
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();

        $user->tokens()->delete();

        return response()->json([
            'message' => 'Logout successful',
            'status' => true
        ]);
    }
}
