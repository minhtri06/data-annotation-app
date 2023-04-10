<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string ',
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response(['message' => 'Wrong email or password'], 400);
        }

        $token = $user->createToken('myAppToken', [$user->role])->plainTextToken;

        return response(['data' => ['user' => $user, 'token' => $token]]);
    }

    public function logout(Request $request)
    {
        /** @var \App\Models\User $user **/
        $user = auth()->user();
        if (!$user) {
            return response(['message' => 'Unauthenticated'], 401);
        }
        $user->tokens()->delete();
        return response()->noContent();
    }
}
