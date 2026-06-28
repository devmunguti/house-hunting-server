<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;


class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();
        if (Auth::attempt($credentials)) {
            $user= $request->user();
            $token= $user->createToken('auth_token')->plainTextToken;
            return [
                "success" => true,
                "user" => $user,
                "token" => $token,
            ];
        }
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        $user = User::create($data);
        return [
            "success" => true,
            "user" => $user
        ];
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return [
            "success" => true,
            "message" => "Logged out successfully"
        ];
    }
}
