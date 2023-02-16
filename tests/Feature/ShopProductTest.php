<?php


namespace Tests\Feature;


use App\DTO\ParentModelDTO;
use App\Exceptions\NoReportException;
use App\Http\Requests\Shops\Products\StoreShopProductRequest;
use App\Http\Requests\Shops\Products\UpdateShopProductRequest;
use App\Models\Product;
use App\Models\Schedule;
use App\Models\Shop;
use App\Models\ShopProduct;
use App\Repositories\ShopProductRepository;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Mockery\MockInterface;
use Tests\TestApiResource;

class ShopProductTest extends TestApiResource
{
    protected function setUpProperties()
    {
        $this->baseRouteName = 'management.shops.products';
        $this->model = new ShopProduct();
        $this->formRequests = [
            'store' => StoreShopProductRequest::class,
            'update' => UpdateShopProductRequest::class,
        ];        $this->parentModelDTOs = [
            ParentModelDTO::make([
                'model' => new Schedule(),
                'foreignKey' => 'schedule_id',
            ]),
            ParentModelDTO::make([
                'model' => new Shop(),
                'foreignKey' => 'shop_id',
                'needInRoute' => true,
            ]),
            ParentModelDTO::make([
                'model' => new Product(),
                'foreignKey' => 'product_id',
            ]),
        ];
    }

    protected function seedsBD(): array
    {
        $productModel = $this->parentModelDTOs[2]->model;
        $result = [];
        for ($i = 0; $i < 10; $i++){
            $result[] = $this->model
                ->factory([
                    'shop_id' => $this->parentModelsIds['shop_id'],
                    'product_id' => $productModel->factory()->create()['id'],
                ])
                ->create()
                ->toArray();
        }
        return $result;
    }

    public function testGetAllWithCache()
    {
        $seeds = $this->seedsBD();
        $prepared = [];
        foreach ($seeds as $item) {
            if (!isset($prepared[$item['product_id']])) {
                $prepared[$item['product_id']] = [];
            }
            $prepared[$item['product_id']][] = $item;
        }
        $response = $this
            ->withHeaders(['Accept' => 'application/json'])
            ->get(route('shops.products.getAll').'?groupBy=products&withCache=true');
        $response->assertStatus(
            Response::HTTP_OK
        );
        $this->assertEquals($response->json(), $prepared);
        $this->assertEquals(
            Cache::tags(['main_data', 'shop_products'])
                ->get('cache_shop_products_group_products', null),
            $prepared
        );
        $this->mock(
            ShopProductRepository::class,
            function (MockInterface $mock) {
                $mock->shouldReceive('getAll')->andThrowExceptions([
                    new NoReportException('test'),
                ]);
            }
        );
        $response2 = $this
            ->withHeaders(['Accept' => 'application/json'])
            ->get(route('shops.products.getAll').'?groupBy=products&withCache=true');
        $response2->assertStatus(
            Response::HTTP_OK
        );
        $this->assertEquals($response2->json(), $prepared);
    }

    public function testGetAll()
    {
        $seeds = $this->seedsBD();
        $response = $this
            ->withHeaders(['Accept' => 'application/json'])
            ->get(route('shops.products.getAll'));
        $response->assertStatus(
            Response::HTTP_OK
        );
        $this->assertEquals($response->json(), $seeds);
    }

    public function testGetAllGroupByProducts()
    {
        $seeds = $this->seedsBD();
        $response = $this
            ->withHeaders(['Accept' => 'application/json'])
            ->get(route('shops.products.getAll').'?groupBy=products');
        $response->assertStatus(
            Response::HTTP_OK
        );
        $prepared = [];
        foreach ($seeds as $item) {
            if (!isset($prepared[$item['product_id']])) {
                $prepared[$item['product_id']] = [];
            }
            $prepared[$item['product_id']][] = $item;
        }
        $this->assertEquals($response->json(), $prepared);
    }

    public function testGetAllGroupByShops()
    {
        $seeds = $this->seedsBD();
        $response = $this
            ->withHeaders(['Accept' => 'application/json'])
            ->get(route('shops.products.getAll').'?groupBy=shops');
        $response->assertStatus(
            Response::HTTP_OK
        );
        $prepared = [];
        foreach ($seeds as $item) {
            if (!isset($prepared[$item['shop_id']])) {
                $prepared[$item['shop_id']] = [];
            }
            $prepared[$item['shop_id']][] = $item;
        }
        $this->assertEquals($response->json(), $prepared);
    }

    public function testStoreRouteAlreadyException()
    {
        $params = $this->model
            ->factory($this->parentModelsIds)
            ->make()
            ->toArray();
        $params = $this->preparedByFormRequest('store', $params);
        $this
            ->withHeaders(['Accept' => 'application/json'])
            ->post(route($this->baseRouteName . '.store', $this->sequenceIds), $params);
        $response = $this
            ->withHeaders(['Accept' => 'application/json'])
            ->post(route($this->baseRouteName . '.store', $this->sequenceIds), $params);
        $response->assertStatus(
            Response::HTTP_CONFLICT
        );
        $equalResponse = config('exceptions.product_already_in_shop');
        unset($equalResponse['http_code']);
        $this->assertEquals($response->json(), $equalResponse);
    }

    public function testStoreRouteUnknownException()
    {
        $exceptionMessage = uniqid('test_exception_');
        $this->mock(
            ShopProductRepository::class,
            function (MockInterface $mock) use ($exceptionMessage) {
                $mock->shouldReceive('store')
                    ->andThrowExceptions([
                        new \Illuminate\Database\QueryException(
                            'insert somebody...',
                            [],
                            new \Exception($exceptionMessage, 500)
                        ),
                    ]);
            }
        );
        $params = $this->model
            ->factory($this->parentModelsIds)
            ->make()
            ->toArray();
        $params = $this->preparedByFormRequest('store', $params);
        $response = $this
            ->withHeaders(['Accept' => 'application/json'])
            ->post(route($this->baseRouteName . '.store', $this->sequenceIds), $params);
        $response->assertStatus(
            Response::HTTP_INTERNAL_SERVER_ERROR
        );
        $this->assertTrue(strpos($response->json()['message'], $exceptionMessage) >= 0);
    }
}
