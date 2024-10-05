<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthAPIController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|min:3|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|max:20',
            'confirm_password' => 'required|same:password'
        ]);

        $data['password'] = bcrypt($data['password']);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
        ]);

        $token = $user->createToken('access-token')->plainTextToken;

        return response()->json(compact('user', 'token'), 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = request()->user();
            $user->tokens()->delete();

            $token = $user->createToken('access-token')->plainTextToken;
            return response()->json(compact('user', 'token'), 200);
        } else {
            return response()->json(['error' => 'Unauthorized.'], 403);
        }
    }
}
