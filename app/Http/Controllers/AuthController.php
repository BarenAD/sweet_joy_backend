<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUser;
use App\Http\Requests\RegisterUser;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(RegisterUser $request)
    {
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $result = $user;
        $result['token'] = $user->createToken($request->userAgent())->plainTextToken;

        return response()->json($result, 200);
    }

    public function login(LoginUser $request)
    {
        $user = User::where('login', $request->login)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
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
