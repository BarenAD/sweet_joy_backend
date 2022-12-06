<?php


namespace Tests\Feature;


use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Laravel\Sanctum\PersonalAccessToken;
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
        $params['token'] = $responseJson['token'];
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
        $params['token'] = $responseJson['token'];
        $this->assertEquals($responseJson, $params);
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
                'Authorization' => 'Bearer ' . $loginResponseJson['token']
            ])
            ->post(route('auth.logout'));
        $response->assertStatus(
        Response::HTTP_OK
        );
        $this->assertTrue(
            PersonalAccessToken::query()
                ->where('tokenable_id', $loginResponseJson['id'])
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
                'Authorization' => 'Bearer ' . $loginResponseJson['token']
            ])
            ->post(route('auth.logoutAll'));
        $response->assertStatus(
            Response::HTTP_OK
        );
        $this->assertTrue(
            PersonalAccessToken::query()
                ->where('tokenable_id', $loginResponseJson['id'])
                ->count()
            ===
            0
        );
    }
}
