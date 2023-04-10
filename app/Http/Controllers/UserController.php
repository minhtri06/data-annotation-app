<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    private $USER_NOT_FOUND_RESPONSE = [['message' => 'User not found'], 404];

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response(['data' => User::all()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string',
            'role' => 'required|string',
        ]);
        $fields['password'] = bcrypt($fields['password']);

        $user = User::create($fields);

        $token = $user->createToken('myAppToken', [$user->role])->plainTextToken;

        return response(['data' => ['user' => $user, 'token' => $token]], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if (!is_int($id)) {
            return response(['message' => 'User id must be integer'], 400);
        }
        $user = User::find($id);
        if (!$user) {
            return response(...$this->USER_NOT_FOUND_RESPONSE);
        }
        return response(['data' => ['user' => $user]]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if (!is_int($id)) {
            return response(['message' => 'User id must be integer'], 400);
        }

        $user = User::find($id);
        if ($user) {
            return response(...$this->USER_NOT_FOUND_RESPONSE);
        }

        $fields = $request->validate([
            'name' => 'string',
            'password' => 'string'
        ]);
        if ($fields['password']) {
            $fields['password'] = bcrypt($fields['password']);
        }
        $user->update($fields);
        return response([
            'message' => 'User updated',
            'data' => ['user' => $user]
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!is_int($id)) {
            return response(['message' => 'User id must be integer'], 400);
        }
        $user = User::find($id);
        if (!$user) {
            return response(...$this->USER_NOT_FOUND_RESPONSE);
        }
        $user->delete();
        return response(['message' => 'User deleted']);
    }
}
