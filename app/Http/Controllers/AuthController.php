<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\AuthLoginRequest;
use App\Http\Requests\Auth\AuthRegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(AuthRegisterRequest $request)
    {
        $params = $request->validated();
        $params['password'] = bcrypt($params['password']);
        $user = User::create($params);
        $result = $user;
        $result['token'] = $user->createToken($request->userAgent(), [])->plainTextToken;

        return response()->json($result, 200);
    }

    public function login(AuthLoginRequest $request)
    {
        $params = $request->validated();
        $user = User::where('login', $params['login'])->first();

        if (!$user || !Hash::check($params['password'], $user->password)) {
            return response()->json(['error' => 'Логин или пароль неверные.'], 401);
        }
        $result = $user;
        $result['token'] = $user->createToken($request->userAgent())->plainTextToken;

        return response()->json($result, 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
    }

    public function allLogout(Request $request)
    {
        $request->user()->tokens()->delete();
    }
}
