<?php

namespace App\Http\Controllers;

use App\Exceptions\NoReportException;
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
        $params['password'] = Hash::make($params['password']);
        try {
            $user = User::create($params);
        } catch (\Illuminate\Database\QueryException $exception) {
            if ($exception->getCode() == 23000) {
                throw new NoReportException('user_already_exists');
            }
            throw $exception;
        }
        $result = $user;
        $result['token'] = $user->createToken($request->userAgent(), [])->plainTextToken;

        return response()->json($result, 200);
    }

    public function login(AuthLoginRequest $request)
    {
        $params = $request->validated();
        $user = User::where('email', $params['email'])->first();

        if (!$user || !Hash::check($params['password'], $user->password)) {
            throw new NoReportException('invalid_login');
        }
        $result = $user;
        $result['token'] = $user->createToken($request->userAgent())->plainTextToken;

        return response()->json($result, 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json("OK", 200);
    }

    public function logoutAll(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json("OK", 200);
    }
}
