<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Validator;

class AuthController extends Controller
{
    /**
     * Register a user
     *
     * @bodyParam name string Name of the user. Example: John Doe
     * @bodyParam email string Email of the user. Example: johndoe@example.com
     * @bodyParam password string Password of the user. Example: someStrongPassword
     * @bodyParam confirm_password string Retyped password for confirmation. Example: someStrongPassword
     * @response {
     *  "message": "Successfully created user!",
     *  "accessToken": "{{access_token}}"
     * }
     * @unauthenticated
     * @group Auth APIs
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users',
            'password' => 'required|string',
            'confirm_password' => 'required|same:password'
        ]);

        $user = new User([
            'name'  => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        if ($user->save()) {
            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->plainTextToken;

            return response()->json([
                'message' => 'Successfully created user!',
                'accessToken' => $token,
            ], 201);
        } else {
            return response()->json(['error' => 'Provide proper details']);
        }
    }

    /**
     * Login user and create token
     *
     * @bodyParam email string Email of the user. Example: johndoe@example.com
     * @bodyParam password string Password of the user. Example: someStrongPassword
     * @response {
     *  "accessToken": "{{access_token}}"
     *  "token_type": "Bearer"
     * }
     * @unauthenticated
     * @group Auth APIs
     */

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        $credentials = request(['email', 'password']);
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->plainTextToken;

        return response()->json([
            'accessToken' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    /**
     * Get the authenticated User
     *
     * @response {
     *  "id": 2,
     *  "name": "John Doe",
     *  "email": "johndoe@example.com",
     *  "email_verified_at": "2023-12-13T18:07:35.000000Z",
     *  "created_at": "2023-12-13T18:07:35.000000Z",
     *  "updated_at": "2023-12-13T18:07:35.000000Z"
     * }
     * @group Auth APIs
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    /**
     * Logout user (Revoke the token)
     *
     * @group Auth APIs
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
}
