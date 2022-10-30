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
    protected string $baseRouteName;                                        //Название роута, без метода.
    protected Model $model;                                                 //Тестируемая модель
    protected array $only = ['index','show','store','update','destroy'];    //Тестируемые методы
    protected array $except = [];                                           //Исключаемые методы
    protected array $formRequests = [];                                     //Форм-реквесты обработки запросов
    protected array $parentModelDTOs = [];                                  //Родительские модели в порядке вложенности

    protected array $parentModelsIds = [];
    protected array $sequenceIds = [];

    public function setUp(): void {
        parent::setUp();
        if(!isset($this->model) || !isset($this->baseRouteName)) {
            $this->setUpProperties();
            $this->only = array_diff($this->only, $this->except);
        }
        $this->parentModelsIds = [];
        $this->sequenceIds = [];
        foreach ($this->parentModelDTOs as $key => $parentModelDTO) {
            $id = $parentModelDTO
                ->model->factory($this->preparedByFillable($this->parentModelsIds, $parentModelDTO->model->getFillable()))
                ->create()
                ->toArray()['id'];
            $this->parentModelsIds[$parentModelDTO->foreignKey] = $id;
            if ($parentModelDTO->needInRoute) {
                $this->sequenceIds[] = $id;
            }
        }
        $this->parentModelsIds = $this->preparedByFillable($this->parentModelsIds, $this->model->getFillable());
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

    protected function preparedByFillable(array $params, array $fillable)
    {
        return array_flip(array_intersect(array_flip($params), $fillable));
    }

    protected function seedsBD(): array
    {
        return $this->model
            ->factory($this->parentModelsIds)
            ->count(10)
            ->create()
            ->toArray();
    }

    public function testIndexRoute()
    {
        $this->checkNeedTest('index');
        $customParams = $this->seedsBD();
        $this->preparedByFormRequest('index', $customParams);
        $response = $this
            ->withHeaders(['Accept' => 'application/json'])
            ->get(route($this->baseRouteName . '.index', $this->sequenceIds));
        $response->assertStatus(
            Response::HTTP_OK
        );
        $this->assertEquals($response->json(), $customParams);
    }

    public function testShowRoute()
    {
        $this->checkNeedTest('show');
        $customParam = $this->seedsBD()[rand(0, 9)];
        $this->sequenceIds[] = $customParam['id'];
        $response = $this
            ->withHeaders(['Accept' => 'application/json'])
            ->get(route($this->baseRouteName . '.show', $this->sequenceIds));
        $response->assertStatus(
            Response::HTTP_OK
        );
        $this->assertEquals($response->json(), $customParam);
    }

    public function testStoreRoute()
    {
        $this->checkNeedTest('store');
        $params = $this->model
            ->factory($this->parentModelsIds)
            ->make()
            ->toArray();
        $params = $this->preparedByFormRequest('store', $params);
        $response = $this
            ->withHeaders(['Accept' => 'application/json'])
            ->post(route($this->baseRouteName . '.store', $this->sequenceIds), $params);
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
        $item = $this->model
            ->factory($this->parentModelsIds)
            ->create();
        $newParams = $this->model
            ->factory($this->parentModelsIds)
            ->make()
            ->toArray();
        $newParams = $this->preparedByFormRequest('update', $newParams);
        $this->sequenceIds[] = $item['id'];
        $response = $this
            ->withHeaders(['Accept' => 'application/json'])
            ->put(route($this->baseRouteName . '.update', $this->sequenceIds), $newParams);
        $response->assertStatus(
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
        $item = $this->model
            ->factory($this->parentModelsIds)
            ->create();
        $this->sequenceIds[] = $item['id'];
        $response2 = $this
            ->withHeaders(['Accept' => 'application/json'])
            ->delete(route($this->baseRouteName . '.destroy', $this->sequenceIds));
        $response2->assertStatus(
            Response::HTTP_OK
        );
        $this->assertModelMissing($item);
    }
}
