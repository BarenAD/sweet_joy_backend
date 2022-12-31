<?php

namespace App\Http\Controllers;

use App\Exceptions\NoReportException;
use App\Http\Requests\Auth\AuthLoginRequest;
use App\Http\Requests\Auth\AuthRegisterRequest;
use App\Http\Utils\UserPermissionUtil;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    private UserRepository $userRepository;
    private UserPermissionUtil $userPermissionUtil;

    public function __construct(
        UserRepository $userRepository,
        UserPermissionUtil $userPermissionUtil
    ) {
        $this->userRepository = $userRepository;
        $this->userPermissionUtil = $userPermissionUtil;
    }

    public function register(AuthRegisterRequest $request)
    {
        $params = $request->validated();
        $params['password'] = Hash::make($params['password']);
        try {
            $user = $this->userRepository->store($params);
        } catch (\Illuminate\Database\QueryException $exception) {
            if ($exception->getCode() == 23000) {
                throw new NoReportException('user_already_exists');
            }
            throw $exception;
        }
        $result = $user;
        $result['token'] = $user->createToken($request->userAgent(), [])->plainTextToken;
        $result['permissions'] = [];

        return response()->json($result, 200);
    }

    public function login(AuthLoginRequest $request)
    {
        $params = $request->validated();
        try {
            $user = $this->userRepository->getUserByEmail($params['email']);
        } catch (\Throwable $exception) {
            throw new NoReportException('invalid_login');
        }
        if (!Hash::check($params['password'], $user->password)) {
            throw new NoReportException('invalid_login');
        }
        $result = $user;
        $result['token'] = $user->createToken($request->userAgent())->plainTextToken;
        try {
            $result['permissions'] = $this->userPermissionUtil->getUserPermissions($user->id);
        } catch (\Throwable $exception) {
            $result['permissions'] = [];
        }

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
