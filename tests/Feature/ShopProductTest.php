<?php


namespace Tests\Feature;


use App\DTO\ParentModelDTO;
use App\Http\Requests\Shops\Products\StoreShopProductRequest;
use App\Http\Requests\Shops\Products\UpdateShopProductRequest;
use App\Models\Product;
use App\Models\Schedule;
use App\Models\Shop;
use App\Models\ShopProduct;
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
        ];
        $this->parentModelDTOs = [
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
}
