<?php


namespace Tests\Feature;


use App\Models\Category;
use Tests\TestApiResource;

class CategoryTest extends TestApiResource
{
    protected function setUpProperties()
    {
        $this->baseRouteName = 'management.categories';
        $this->model = new Category();
    }
}
