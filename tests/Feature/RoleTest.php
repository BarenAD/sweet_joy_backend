<?php


namespace Tests\Feature;


use App\Models\Role;
use Tests\TestApiResource;

class RoleTest extends TestApiResource
{
    protected function setUpProperties()
    {
        $this->baseRouteName = 'management.roles';
        $this->model = new Role();
    }
}
