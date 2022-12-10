<?php

namespace Tests\Feature;

use DemoDBSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Tests\Traits\WithoutPermissionsTrait;

class MasterTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    use WithoutMiddleware;
    use WithoutPermissionsTrait;

    protected DemoDBSeeder $seeder;
    protected array $responseEqual;

    public function setUp(): void {
        parent::setUp();
        $this->seeder = new DemoDBSeeder();
        $this->seeder->count = 10;
        $this->seeder->handleSeed();
        $this->responseEqual = $this->makeResponseEqual();
    }

    public function testMasterData()
    {
        $response = $this
            ->withHeaders(['Accept' => 'application/json'])
            ->get(route('master.data'));
        $response->assertStatus(
            Response::HTTP_OK
        );
        $this->assertEquals($response->json(), $this->responseEqual);
    }

    protected function makeResponseEqual()
    {
        $pathToImages = config('filesystems.path_inside_disk.products.images');
        $pathToImagesMini = config('filesystems.path_inside_disk.products.images_mini');
        $groupedProductCategories = $this->seeder->productCategories->groupBy('product_id');
        $preparedProducts = array_map(function ($product) use (
            $pathToImages,
            $pathToImagesMini,
            $groupedProductCategories
        ) {
            $product['image_mini'] = Storage::disk('public')->url($pathToImagesMini.$product['image']);
            $product['image'] = Storage::disk('public')->url($pathToImages.$product['image']);
            $product['categories'] = $groupedProductCategories[$product['id']]->pluck('category_id')->toArray();
            return $product;
        }, $this->seeder->products->toArray());

        $preparedShops = array_map(function ($shop) {
            $shop['schedule'] = $this->seeder->schedules[$shop['schedule_id']]->toArray();
            return $shop;
        }, $this->seeder->shops->toArray());

        $pathToDocuments = config('filesystems.path_inside_disk.documents');
        $preparedDocuments = [];
        foreach ($this->seeder->documentLocations->toArray() as $key => $documentLocation){
            $document = $this->seeder->documents[$documentLocation['document_id']]->toArray();
            $document['url'] = Storage::disk('public')->url($pathToDocuments.$document['urn']);
            $document['location'] = $documentLocation['identify'];
            unset($document['urn']);
            $preparedDocuments[$documentLocation['identify']] =  $document;
        }

        return [
            'categories' => array_values($this->seeder->categories->toArray()),
            'products' => array_values($preparedProducts),
            'shops' => array_values($preparedShops),
            'shop_products' => $this->seeder->shopProducts->groupBy('product_id')->toArray(),
            'documents' => $preparedDocuments,
            'site_configurations' => $this->seeder->siteConfigurations->keyBy('identify')->toArray(),
        ];
    }
}
