<?php


namespace Tests\Feature;


use App\Models\Permission;
use Tests\TestApiResource;

class PermissionTest extends TestApiResource
{
    protected function setUpProperties()
    {
        $this->baseRouteName = 'management.permissions';
        $this->model = new Permission();
        $this->only = ['index','show'];
    }
}
