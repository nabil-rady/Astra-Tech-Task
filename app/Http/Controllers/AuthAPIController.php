<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OA;


class AuthAPIController extends Controller
{
    #[OA\Post(
        path: "/api/register",
        operationId: "register",
        tags: ['Auth'],
        description: "Register and logs user in and returns access token"
    )]
    #[OA\RequestBody(
        required: true,
        content: [
            'application/json' => new OA\MediaType(
                mediaType: "application/json",
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(
                            property: "name",
                            type: "string",
                            example: "nabil"
                        ),
                        new OA\Property(
                            property: "email",
                            type: "string",
                            example: "example@example.com"
                        ),
                        new OA\Property(
                            property: "password",
                            type: "string",
                            example: "12345678"
                        ),
                        new OA\Property(
                            property: "confirm_password",
                            type: "string",
                            example: "12345678"
                        )
                    ],
                    required: ["name", "email", "password", "confirm_password"]
                )
            )
        ]
    )]
    #[OA\Response(
        response: 201,
        description: "Successful registration"
    )]
    #[OA\Response(response: 422, description: "Bad request")]
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


    #[OA\Post(
        path: "/api/login",
        operationId: "login",
        tags: ['Auth'],
        description: "Logs user in and returns access token"
    )]
    #[OA\RequestBody(
        required: true,
        content: [
            'application/json' => new OA\MediaType(
                mediaType: "application/json",
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(
                            property: "email",
                            type: "string",
                            example: "example@example.com"
                        ),
                        new OA\Property(
                            property: "password",
                            type: "string",
                            example: "12345678"
                        )
                    ],
                    required: ["email", "password"]
                )
            )
        ]
    )]
    #[OA\Response(
        response: 200,
        description: "Successful login"
    )]
    #[OA\Response(response: 422, description: "Bad request")]
    #[OA\Response(response: 403, description: "Incorrect login")]
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
