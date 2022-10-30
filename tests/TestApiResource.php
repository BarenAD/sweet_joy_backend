<?php


namespace Tests;

use Database\Factories\CategoryFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Tests\Traits\WithoutPermissionsTrait;

abstract class TestApiResource extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    use WithoutMiddleware;
    use WithoutPermissionsTrait;

    abstract protected function setUpProperties();
    protected string $baseRouteName;
    protected Model $model;
    protected array $only = ['index','show','store','update','destroy'];
    protected array $except = [];
    protected array $formRequests = [];

    public function setUp(): void {
        parent::setUp();
        if(!isset($this->model) || !isset($this->baseRouteName)) {
            $this->setUpProperties();
            $this->only = array_diff($this->only, $this->except);
        }
    }

    protected function checkNeedTest(string $action)
    {
        if (array_search($action, $this->only) === false) {
            $this->markTestSkipped();
        }
    }

    protected function preparedByFormRequest(string $action, $params)
    {
        if (isset($this->formRequests[$action])) {
            return Validator::make($params, $this->formRequests[$action]::rules())->validate();
        }
        return $params;
    }

    public function testIndexRoute()
    {
        $this->checkNeedTest('index');
        $customParams = $this->model->factory()->count(10)->create()->toArray();
        $this->preparedByFormRequest('index', $customParams);
        $response = $this
            ->withHeaders(['Accept' => 'application/json'])
            ->get(route($this->baseRouteName . '.index'));
        $response->assertStatus(
            Response::HTTP_OK
        );
        $this->assertEquals($response->json(), $customParams);
    }

    public function testShowRoute()
    {
        $this->checkNeedTest('show');
        $customParam = $this->model->factory()->count(10)->create()->toArray()[rand(0, 9)];
        $response = $this
            ->withHeaders(['Accept' => 'application/json'])
            ->get(route($this->baseRouteName . '.show', $customParam['id']));
        $response->assertStatus(
            Response::HTTP_OK
        );
        $this->assertEquals($response->json(), $customParam);
    }

    public function testStoreRoute()
    {
        $this->checkNeedTest('store');
        $params = $this->model->factory()->make()->toArray();
        $params = $this->preparedByFormRequest('store', $params);
        $response = $this
            ->withHeaders(['Accept' => 'application/json'])
            ->post(route($this->baseRouteName . '.store'), $params);
        $response->assertStatus(
            Response::HTTP_OK
        );
        $this->assertDatabaseHas(
            $this->model,
            array_merge(
                [
                    'id' => $response['id'],
                ],
                $params,
            )
        );
    }

    public function testUpdateRoute()
    {
        $this->checkNeedTest('update');
        $item = $this->model->factory()->create();
        $newParams = $this->model->factory()->make()->toArray();
        $newParams = $this->preparedByFormRequest('update', $newParams);
        $response2 = $this
            ->withHeaders(['Accept' => 'application/json'])
            ->put(route($this->baseRouteName . '.update', $item['id']), $newParams);
        $response2->assertStatus(
            Response::HTTP_OK
        );
        $this->assertDatabaseHas(
            $this->model,
            array_merge(
                [
                    'id' => $item['id'],
                ],
                $newParams,
            )
        );
    }

    public function testDestroyRoute()
    {
        $this->checkNeedTest('destroy');
        $item = $this->model->factory()->create();
        $response2 = $this
            ->withHeaders(['Accept' => 'application/json'])
            ->delete(route($this->baseRouteName . '.destroy', $item['id']));
        $response2->assertStatus(
            Response::HTTP_OK
        );
        $this->assertModelMissing($item);
    }
}
