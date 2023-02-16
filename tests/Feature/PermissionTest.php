<?php


namespace Tests\Feature;


use App\Models\Permission;
use App\Models\User;
use Illuminate\Http\Response;
use Tests\TestApiResource;

class PermissionTest extends TestApiResource
{
    protected function setUpProperties()
    {
        $this->baseRouteName = 'management.permissions';
        $this->model = new Permission();
        $this->only = ['index','show'];
    }

    public function testProfilePermissions()
    {
        $user = User::factory()->create();
        $token = $user->createToken('Feature Test Client')->plainTextToken;
        $response = $this
            ->withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $token
            ])
            ->get(route('profile.permissions'));

        $response->assertStatus(
            Response::HTTP_OK
        );
        $this->assertEquals($response->json(), []);
    }
}
