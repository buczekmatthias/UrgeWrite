<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Provided credentials are incorrect.'], 404);
        }
        $user->tokens()->delete();

        $token = $user->createToken($request->username . "_tkn")->plainTextToken;

        return response()->json(['username' => $user->username, 'token' => $token], 200);
    }

    public function register(Request $request): JsonResponse
    {
        $valid = $request->validate([
            'username' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($valid) {
            try {
                $user = User::create($valid);
                $user->noteGroups()->create(['name' => 'Unset']);
                $user->taskGroups()->create(['name' => 'Unset']);
                $token = $user->createToken($user->username . "_tkn")->plainTextToken;

                return response()->json(['username' => $user->username, 'token' => $token], 200);
            } catch (\Exception $e) {
                return response()->json(['message' => "Account couldn't be created. Possible duplicate of username or e-mail."], 500);
            }
        }

        return response()->json(['message' => 'Invalid input provided'], 400);
    }

    public function logout(): JsonResponse
    {
        try {
            auth()->user()->tokens()->delete();

            return response()->json(['message' => 'Logged out successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
