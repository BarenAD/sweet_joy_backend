<?php


namespace Tests\Feature;


use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Laravel\Passport\Token;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected User $model;

    protected function setUp(): void
    {
        parent::setUp();
        $this->model = new User();
    }

    public function testRegisterRoute()
    {
        $params = $this->model
            ->factory()
            ->make()
            ->toArray();
        $params['password'] = 'password';
        $response = $this
            ->withHeaders(['Accept' => 'application/json'])
            ->post(route('auth.register'), $params);
        $response->assertStatus(
            Response::HTTP_OK
        );
        $responseJson = $response->json();
        unset($params['email_verified_at']);
        unset($params['password']);
        $params['id'] = $responseJson['id'];
        $params['tokens'] = $responseJson['tokens'];
        $this->assertEquals($responseJson, $params);
    }

    public function testLoginRoute()
    {
        $params = $this->model
            ->factory()
            ->create()
            ->toArray();
        $response = $this
            ->withHeaders(['Accept' => 'application/json'])
            ->post(route('auth.login'), [
                'email' => $params['email'],
                'password' => 'password',
            ]);
        $response->assertStatus(
            Response::HTTP_OK
        );
        $responseJson = $response->json();
        $params['tokens'] = $responseJson['tokens'];
        $this->assertEquals($responseJson, $params);
    }

    public function testRefreshRoute()
    {
        $params = $this->model
            ->factory()
            ->create()
            ->toArray();
        $responseLogin = $this
            ->withHeaders(['Accept' => 'application/json'])
            ->post(route('auth.login'), [
                'email' => $params['email'],
                'password' => 'password',
            ]);
        $responseLogin->assertStatus(
            Response::HTTP_OK
        );
        $responseRefresh = $this
            ->withHeaders([
                'Accept' => 'application/json',
            ])
            ->post(route('auth.refresh'), [
                'refresh_token' => $responseLogin->json()['tokens']['refresh_token'],
            ]);
        $responseRefresh->assertStatus(
            Response::HTTP_OK
        );
        $responseLogoutOldToken = $this
            ->withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $responseLogin->json()['tokens']['access_token']
            ])
            ->post(route('auth.logout'));
        $responseLogoutOldToken->assertStatus(
            Response::HTTP_UNAUTHORIZED
        );
        $responseLogoutNewToken = $this
            ->withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $responseRefresh->json()['access_token']
            ])
            ->post(route('auth.logout'));
        $responseLogoutNewToken->assertStatus(
            Response::HTTP_OK
        );
    }

    public function testInvalidLoginRoute()
    {
        $params = $this->model
            ->factory()
            ->create()
            ->toArray();
        $response = $this
            ->withHeaders(['Accept' => 'application/json'])
            ->post(route('auth.login'), [
                'email' => $params['email'],
                'password' => 'incorrect_password',
            ]);
        $response->assertStatus(
            Response::HTTP_UNAUTHORIZED
        );
        $equalResponse = config('exceptions.invalid_login');
        unset($equalResponse['http_code']);
        $this->assertEquals($response->json(), $equalResponse);
    }

    public function testLogoutRoute()
    {
        $params = $this->model
            ->factory()
            ->create()
            ->toArray();
        $loginResponse = $this
            ->withHeaders(['Accept' => 'application/json'])
            ->post(route('auth.login'), [
                'email' => $params['email'],
                'password' => 'password',
            ]);
        $loginResponseJson = $loginResponse->json();
        $response = $this
            ->withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $loginResponseJson['tokens']['access_token']
            ])
            ->post(route('auth.logout'));
        $response->assertStatus(
        Response::HTTP_OK
        );
        $this->assertTrue(
            Token::query()
                ->where('user_id', $loginResponseJson['id'])
                ->where('revoked', 0)
                ->count()
            ===
            0
        );
    }

    public function testAllLogoutRoute()
    {
        $params = $this->model
            ->factory()
            ->create()
            ->toArray();
        $this
            ->withHeaders(['Accept' => 'application/json'])
            ->post(route('auth.login'), [
                'email' => $params['email'],
                'password' => 'password',
            ]);
        $this
            ->withHeaders(['Accept' => 'application/json'])
            ->post(route('auth.login'), [
                'email' => $params['email'],
                'password' => 'password',
            ]);
        $loginResponse = $this
            ->withHeaders(['Accept' => 'application/json'])
            ->post(route('auth.login'), [
                'email' => $params['email'],
                'password' => 'password',
            ]);
        $loginResponseJson = $loginResponse->json();
        $response = $this
            ->withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $loginResponseJson['tokens']['access_token']
            ])
            ->post(route('auth.logoutAll'));
        $response->assertStatus(
            Response::HTTP_OK
        );
        $this->assertTrue(
            Token::query()
                ->where('user_id', $loginResponseJson['id'])
                ->where('revoked', 0)
                ->count()
            ===
            0
        );
    }
}
