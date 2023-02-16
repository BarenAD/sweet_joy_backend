<?php


namespace Tests\Feature;


use App\DTO\ParentModelDTO;
use App\Exceptions\NoReportException;
use App\Http\Requests\Shops\StoreShopRequest;
use App\Http\Requests\Shops\UpdateShopRequest;
use App\Models\Schedule;
use App\Models\Shop;
use App\Repositories\ShopRepository;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Mockery\MockInterface;
use Tests\TestApiResource;

class ShopTest extends TestApiResource
{
    protected function setUpProperties()
    {
        $this->baseRouteName = 'management.shops';
        $this->model = new Shop();
        $this->formRequests = [
            'store' => StoreShopRequest::class,
            'update' => UpdateShopRequest::class,
        ];
        $this->parentModelDTOs = [
            ParentModelDTO::make([
                'model' => new Schedule(),
                'foreignKey' => 'schedule_id',
            ]),
        ];
    }

    public function testIndexWithCache()
    {
        $customParams = $this->seedsBD();
        foreach ($customParams as &$customParam) {
            $customParam['schedule'] = Schedule::query()->findOrFail($customParam['schedule_id'])->toArray();
        }
        $response = $this
            ->withHeaders(['Accept' => 'application/json'])
            ->get(route('shops.index').'?withCache=true&withSchedules=true');
        $response->assertStatus(
            Response::HTTP_OK
        );
        $this->assertEquals($response->json(), $customParams);
        $this->assertEquals(Cache::tags(['main_data', 'shops'])->get('cache_shops_with_schedules', null), $customParams);
        $this->mock(
            ShopRepository::class,
            function (MockInterface $mock) {
                $mock->shouldReceive('getAllWithSchedules')->andThrowExceptions([
                    new NoReportException('test'),
                ]);
            }
        );
        $response2 = $this
            ->withHeaders(['Accept' => 'application/json'])
            ->get(route('shops.index').'?withCache=true&withSchedules=true');
        $response2->assertStatus(
            Response::HTTP_OK
        );
        $this->assertEquals($response2->json(), $customParams);
    }
}
