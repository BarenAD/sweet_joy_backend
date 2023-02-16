<?php


namespace Tests\Feature;


use App\Exceptions\NoReportException;
use App\Models\User;
use App\Repositories\DocumentRepository;
use App\Repositories\UserRepository;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Laravel\Sanctum\PersonalAccessToken;
use Mockery\MockInterface;
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
        $params['password'] = UserFactory::DEFAULT_USER_PASSWORD;
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
        $params['permissions'] = [];
        $this->assertEquals($responseJson, $params);
    }

    public function testRegisterExistUserRoute()
    {
        $params = $this->model
            ->factory()
            ->create()
            ->toArray();
        $params['password'] = UserFactory::DEFAULT_USER_PASSWORD;
        $response = $this
            ->withHeaders(['Accept' => 'application/json'])
            ->post(route('auth.register'), $params);
        $response->assertStatus(
            Response::HTTP_BAD_REQUEST
        );
        $responseJson = $response->json();
        $this->assertTrue($responseJson['id'] === config('exceptions.user_already_exists.id'));
    }

    public function testRegisterExceptionRoute()
    {
        $exceptionMessage = uniqid('test_exception_');
        $params = $this->model
            ->factory()
            ->make()
            ->toArray();
        $params['password'] = UserFactory::DEFAULT_USER_PASSWORD;
        $this->mock(
            UserRepository::class,
            function (MockInterface $mock) use ($exceptionMessage) {
                $mock->shouldReceive('store')->andThrowExceptions([
                    new \Illuminate\Database\QueryException(
                        'insert somebody...',
                        [],
                        new \Exception($exceptionMessage, 500)
                    ),
                ]);
            }
        );
        $response = $this
            ->withHeaders(['Accept' => 'application/json'])
            ->post(route('auth.register'), $params);
        $response->assertStatus(
            Response::HTTP_INTERNAL_SERVER_ERROR
        );
        $this->assertTrue(strpos($response->json()['message'], $exceptionMessage) >= 0);
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
                'password' => UserFactory::DEFAULT_USER_PASSWORD,
            ]);
        $response->assertStatus(
            Response::HTTP_OK
        );
        $responseJson = $response->json();
        $params['token'] = $responseJson['token'];
        $params['permissions'] = [];
        $this->assertEquals($responseJson, $params);
    }

    public function testLoginExceptionRoute()
    {
        $params = $this->model
            ->factory()
            ->create()
            ->toArray();
        $this->mock(
            UserRepository::class,
            function (MockInterface $mock) {
                $mock->shouldReceive('getUserByEmail')->andThrowExceptions([
                    new NoReportException('test'),
                ]);
            }
        );
        $response = $this
            ->withHeaders(['Accept' => 'application/json'])
            ->post(route('auth.login'), [
                'email' => $params['email'],
                'password' => 'incorrect_password',
            ]);
        $response->assertStatus(
            Response::HTTP_UNAUTHORIZED
        );
        $responseJson = $response->json();
        $this->assertTrue($responseJson['id'] === config('exceptions.invalid_login.id'));
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
                'password' => UserFactory::DEFAULT_USER_PASSWORD,
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
                'password' => UserFactory::DEFAULT_USER_PASSWORD,
            ]);
        $this
            ->withHeaders(['Accept' => 'application/json'])
            ->post(route('auth.login'), [
                'email' => $params['email'],
                'password' => UserFactory::DEFAULT_USER_PASSWORD,
            ]);
        $loginResponse = $this
            ->withHeaders(['Accept' => 'application/json'])
            ->post(route('auth.login'), [
                'email' => $params['email'],
                'password' => UserFactory::DEFAULT_USER_PASSWORD,
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
