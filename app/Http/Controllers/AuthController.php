<?php

namespace App\Http\Controllers;

use App\Exceptions\NoReportException;
use App\Http\Requests\Auth\AuthLoginRequest;
use App\Http\Requests\Auth\AuthRefreshRequest;
use App\Http\Requests\Auth\AuthRegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Laravel\Passport\Client;
use Laravel\Passport\Http\Controllers\AccessTokenController;
use Laravel\Passport\RefreshToken;
use Laravel\Passport\Token;
use Laravel\Passport\TokenRepository;
use Lcobucci\JWT\Parser as JwtParser;
use League\OAuth2\Server\AuthorizationServer;
use Psr\Http\Message\ServerRequestInterface;

class AuthController extends AccessTokenController
{
    private ServerRequestInterface $serverRequest;
    private Client $client;

    public function __construct(
        AuthorizationServer $server,
        TokenRepository $tokens,
        JwtParser $jwt,
        ServerRequestInterface $serverRequest
    ) {
        $this->serverRequest = $serverRequest;
        $this->client = Client::query()->findOrFail(config('auth.clients.passwords'));
        parent::__construct($server, $tokens, $jwt);
    }

    private function getTokensByPassword($email, $password)
    {
        $parsedRequest = $this->serverRequest->withParsedBody([
            'username' => $email,
            'password' => $password,
            'grant_type' => 'password',
            'client_id' => $this->client['id'],
            'client_secret' => $this->client['secret'],
        ]);
        $response = $this->issueToken($parsedRequest);
        return json_decode($response->getContent());
    }

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
        $result = $user->toArray();
        $result['tokens'] = $this->getTokensByPassword($params['email'], $request['password']);

        return response()->json($result);
    }

    public function login(AuthLoginRequest $request)
    {
        $params = $request->validated();
        $user = User::query()->where('email', $params['email'])->first();

        if (!$user || !Hash::check($params['password'], $user->password)) {
            throw new NoReportException('invalid_login');
        }
        $result = $user->toArray();
        $result['tokens'] = $this->getTokensByPassword($params['email'], $params['password']);

        return response()->json($result, 200);
    }

    public function refresh(AuthRefreshRequest $request): JsonResponse
    {
        try {
            $parsedRequest = $this->serverRequest->withParsedBody([
                'scope' => '',
                'refresh_token' => $request['refresh_token'],
                'grant_type' => 'refresh_token',
                'client_id' => $this->client['id'],
                'client_secret' => $this->client['secret']
            ]);

            $response = $this->issueToken($parsedRequest);
            $result = json_decode($response->getContent(), true);

            return response()->json($result);
        } catch (\Exception $exception) {
            throw new NoReportException('invalid_refresh_token');
        }
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json("OK", 200);
    }

    public function allLogout(Request $request)
    {
        $tokens = $request->user()->tokens()->pluck('id');
        DB::beginTransaction();
        Token::query()
            ->whereIn('id', $tokens)
            ->update(['revoked'=> true]);
        RefreshToken::query()
            ->whereIn('access_token_id', $tokens)
            ->update(['revoked' => true]);
        DB::commit();
        return response()->json("OK", 200);
    }
}
