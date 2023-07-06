<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string',
        ]);
        $fields['password'] = bcrypt($fields['password']);

        $user = User::create($fields);

        $token = $user->createToken('myAppToken', [$user->role])->plainTextToken;

        return response(['data' => ['user' => $user, 'token' => $token]], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string ',
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ApiException::Unauthorized('Wrong email or password');
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

    public function resetPassword(Request $request)
    {
        /** @var \App\Models\User $user **/
        $user = auth()->user();

        $request->validate([
            'old_password' => 'required|string',
            'new_password' => 'required|string ',
        ]);

        if (!Hash::check($request->old_password, $user->password)) {
            throw ApiException::Unauthorized('Wrong password');
        }

        $user->password = bcrypt($request->new_password);
        $user->save();

        return response(['message' => 'Reset password successfully']);
    }
}
