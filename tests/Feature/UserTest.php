<?php


namespace Tests\Feature;


use App\Http\Requests\Users\UpdateUserRequest;
use App\Models\User;
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
}
