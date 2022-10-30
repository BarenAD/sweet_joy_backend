<?php


namespace Tests\Feature;


use App\DTO\ParentModelDTO;
use App\Http\Requests\Shops\StoreShopRequest;
use App\Http\Requests\Shops\UpdateShopRequest;
use App\Models\Schedule;
use App\Models\Shop;
use Illuminate\Http\Response;
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
}
