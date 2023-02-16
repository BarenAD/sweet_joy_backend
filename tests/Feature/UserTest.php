<?php


namespace Tests\Feature;


use App\Http\Requests\Users\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Response;
use Tests\TestApiResource;

class UserTest extends TestApiResource
{
    protected function setUpProperties()
    {
        $this->baseRouteName = 'management.users';
        $this->model = new User();
        $this->except = ['store'];
        $this->formRequests = [
            'update' => UpdateUserRequest::class,
        ];
    }

    protected function seedsBD(): array
    {
        return $this->model
            ->factory($this->parentModelsIds)
            ->count(10)
            ->create()
            ->makeVisible('note')
            ->toArray();
    }

    public function testUpdateRoute()
    {
        $user = $this->model
            ->factory()
            ->create();
        $newParams = $this->model
            ->factory()
            ->make()
            ->toArray();
        $newParams['password'] = 'new_password';
        $newParams = $this->preparedByFormRequest('update', $newParams);
        $response = $this
            ->withHeaders(['Accept' => 'application/json'])
            ->put(route($this->baseRouteName . '.update', $user['id']), $newParams);
        $response->assertStatus(
            Response::HTTP_OK
        );
        $newParams['password'] = $this->model->newQuery()->findOrFail($user['id'])->password;
        $this->assertDatabaseHas(
            $this->model,
            array_merge(
                [
                    'id' => $user['id'],
                ],
                $newParams,
            )
        );
    }
}
