<?php

namespace Tests\Feature;

use App\Exceptions\NoReportException;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Repositories\ProductRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Mockery\MockInterface;
use Tests\TestCase;
use Tests\Traits\WithoutPermissionsTrait;

class ProductTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    use WithoutMiddleware;
    use WithoutPermissionsTrait;

    private Product $product;
    private Collection $productCategories;
    private string $pathToImages;
    private string $pathToImagesMini;
    private array $params;

    private function setUpParams(): void {
        $this->productCategories = Category::factory()->count(10)->create();
        $this->params = [
            'image' => UploadedFile::fake()->image('test.png', 1000, 1000),
            'name' => $this->faker->text(50),
            'composition' => $this->faker->text(50),
            'manufacturer' => $this->faker->text(50),
            'description' => $this->faker->text(100),
            'product_unit' => $this->faker->text(10),
            'categories' => json_encode($this->productCategories->pluck('id')->toArray()),
        ];
    }

    public function setUp(): void {
        parent::setUp();
        $this->setUpParams();
        $this->product = new Product();
        $this->pathToImages = config('filesystems.path_inside_disk.products.images');
        $this->pathToImagesMini = config('filesystems.path_inside_disk.products.images_mini');
    }

    public function testIndexWithCache()
    {
        $products = $this->product
            ->factory()
            ->count(10)
            ->create()
            ->toArray();
        $response = $this
            ->withHeaders(['Accept' => 'application/json'])
            ->get(route('products.index').'?withCache=true');
        $response->assertStatus(
            Response::HTTP_OK
        );
        foreach ($products as &$product) {
            $product['image_mini'] = Storage::disk('public')->url($this->pathToImagesMini.$product['image']);
            $product['image'] = Storage::disk('public')->url($this->pathToImages.$product['image']);
            $product['categories'] = [];
        }
        $this->assertEquals($response->json(), $products);
        $this->assertEquals(Cache::tags(['main_data', 'products'])->get('cache_products', null), $products);
        $this->mock(
            ProductRepository::class,
            function (MockInterface $mock) {
                $mock->shouldReceive('getAll')->andThrowExceptions([
                    new NoReportException('test'),
                ]);
            }
        );
        $response2 = $this
            ->withHeaders(['Accept' => 'application/json'])
            ->get(route('products.index').'?withCache=true');
        $response2->assertStatus(
            Response::HTTP_OK
        );
        $this->assertEquals($response2->json(), $products);
    }

    public function testIndexProduct()
    {
        $products = $this->product
            ->factory()
            ->count(10)
            ->create()
            ->toArray();
        $response = $this
            ->withHeaders(['Accept' => 'application/json'])
            ->get(route('management.products.index'));
        $response->assertStatus(
            Response::HTTP_OK
        );
        foreach ($products as &$product) {
            $product['image_mini'] = Storage::disk('public')->url($this->pathToImagesMini.$product['image']);
            $product['image'] = Storage::disk('public')->url($this->pathToImages.$product['image']);
            $product['categories'] = [];
        }
        $this->assertEquals($response->json(), $products);
    }

    public function testIndexProductWithCategories()
    {
        $products = $this->product
            ->factory()
            ->count(10)
            ->create()
            ->toArray();
        foreach ($products as &$product) {
            $categoryIds = [];
            foreach (array_rand($this->productCategories->toArray(), 5) as $index) {
                ProductCategory::query()->create([
                    'product_id' => $product['id'],
                    'category_id' => $this->productCategories[$index]->id,
                ]);
                $categoryIds[] = $this->productCategories[$index]->id;
            }
            $product['image_mini'] = Storage::disk('public')->url($this->pathToImagesMini.$product['image']);
            $product['image'] = Storage::disk('public')->url($this->pathToImages.$product['image']);
            $product['categories'] = $categoryIds;
        }
        $response = $this
            ->withHeaders(['Accept' => 'application/json'])
            ->get(route('management.products.index').'?withCategories=true');
        $response->assertStatus(
            Response::HTTP_OK
        );
        $this->assertEquals($response->json(), $products);
    }

    public function testShowProduct()
    {
        $product = $this->product
            ->factory()
            ->count(10)
            ->create()
            ->toArray()
            [rand(0, 9)];
        $response = $this
            ->withHeaders(['Accept' => 'application/json'])
            ->get(route('management.products.show', $product['id']));
        $response->assertStatus(
            Response::HTTP_OK
        );
        $product['image_mini'] = Storage::disk('public')->url($this->pathToImagesMini.$product['image']);
        $product['image'] = Storage::disk('public')->url($this->pathToImages.$product['image']);
        $product['categories'] = [];
        $this->assertEquals($response->json(), $product);
    }

    public function testStoreProduct()
    {
        $response = $this
            ->withHeaders(['Accept' => 'application/json'])
            ->post(route('management.products.store'), $this->params);
        $response->assertStatus(
            Response::HTTP_OK
        );
        $responseIndex = $this
            ->withHeaders(['Accept' => 'application/json'])
            ->get(route('management.products.index'));
        $responseIndex->assertStatus(
            Response::HTTP_OK
        );
        $this->assertDatabaseHas('products', [
            'id' => $response['id'],
            'name' => $this->params['name'],
            'composition' => $this->params['composition'],
            'manufacturer' => $this->params['manufacturer'],
            'description' => $this->params['description'],
            'product_unit' => $this->params['product_unit'],
        ]);
        $this->assertTrue(
            ProductCategory::query()
                ->where('product_id', $response['id'])
                ->count()
            ===
            $this->productCategories->count()
        );
        $productImageName = last(explode('/',$response['image']));
        $this->assertTrue(Storage::disk('public')->exists($this->pathToImages.$productImageName));
        $this->assertTrue(Storage::disk('public')->exists($this->pathToImagesMini.$productImageName));
        Storage::disk('public')->delete($this->pathToImages.$productImageName);
        Storage::disk('public')->delete($this->pathToImagesMini.$productImageName);
    }

    public function testFindProduct()
    {
        $responseStore = $this
            ->withHeaders(['Accept' => 'application/json'])
            ->post(route('management.products.store'), $this->params);
        $responseShowJson = $responseStore->json();
        $responseShow = $this
            ->withHeaders(['Accept' => 'application/json'])
            ->get(route('management.products.show', $responseShowJson['id']));
        $productImageName = last(explode('/',$responseShowJson['image']));
        $preparedEquals = [
            'id' => $responseShowJson['id'],
            'name' => $this->params['name'],
            'composition' => $this->params['composition'],
            'manufacturer' => $this->params['manufacturer'],
            'description' => $this->params['description'],
            'product_unit' => $this->params['product_unit'],
            'categories' => [],
            'image' => Storage::disk('public')->url($this->pathToImages . $productImageName),
            'image_mini' => Storage::disk('public')->url($this->pathToImagesMini.$productImageName),
        ];
        $this->assertEquals($responseShow->json(), $preparedEquals);
        $productImageName = last(explode('/',$responseStore['image']));
        Storage::disk('public')->delete($this->pathToImages.$productImageName);
        Storage::disk('public')->delete($this->pathToImagesMini.$productImageName);
    }

    public function testFindProductWitchCategories()
    {
        $responseStore = $this
            ->withHeaders(['Accept' => 'application/json'])
            ->post(route('management.products.store'), $this->params);
        $responseShowJson = $responseStore->json();
        $responseShow = $this
            ->withHeaders(['Accept' => 'application/json'])
            ->get(route('management.products.show', $responseShowJson['id']).'?withCategories=true');
        $productImageName = last(explode('/',$responseShowJson['image']));
        $preparedEquals = [
            'id' => $responseShowJson['id'],
            'name' => $this->params['name'],
            'composition' => $this->params['composition'],
            'manufacturer' => $this->params['manufacturer'],
            'description' => $this->params['description'],
            'product_unit' => $this->params['product_unit'],
            'categories' => json_decode($this->params['categories']),
            'image' => Storage::disk('public')->url($this->pathToImages . $productImageName),
            'image_mini' => Storage::disk('public')->url($this->pathToImagesMini.$productImageName),
        ];
        $this->assertEquals($responseShow->json(), $preparedEquals);
        $productImageName = last(explode('/',$responseStore['image']));
        Storage::disk('public')->delete($this->pathToImages.$productImageName);
        Storage::disk('public')->delete($this->pathToImagesMini.$productImageName);
    }

    public function testStoreProductWithoutCategories()
    {
        $this->params['categories'] = "[]";
        $response = $this
            ->withHeaders(['Accept' => 'application/json'])
            ->post(route('management.products.store'), $this->params);
        $response->assertStatus(
            Response::HTTP_OK
        );
        $responseIndex = $this
            ->withHeaders(['Accept' => 'application/json'])
            ->get(route('management.products.index'));
        $responseIndex->assertStatus(
            Response::HTTP_OK
        );
        $this->assertDatabaseHas('products', [
            'id' => $response['id'],
            'name' => $this->params['name'],
            'composition' => $this->params['composition'],
            'manufacturer' => $this->params['manufacturer'],
            'description' => $this->params['description'],
            'product_unit' => $this->params['product_unit'],
        ]);
        $this->assertTrue(
            ProductCategory::query()
                ->where('product_id', $response['id'])
                ->count()
            ===
            0
        );
        $productImageName = last(explode('/',$response['image']));
        $this->assertTrue(Storage::disk('public')->exists($this->pathToImages.$productImageName));
        $this->assertTrue(Storage::disk('public')->exists($this->pathToImagesMini.$productImageName));
        Storage::disk('public')->delete($this->pathToImages.$productImageName);
        Storage::disk('public')->delete($this->pathToImagesMini.$productImageName);
    }

    public function testUpdateProduct()
    {
        $countCategories = $this->productCategories->count();
        $response = $this
            ->withHeaders(['Accept' => 'application/json'])
            ->post(route('management.products.store'), $this->params);

        $productCategories1 = $this->productCategories;
        $this->setUpParams();
        $productCategories2 = $this->productCategories;
        $productImageName = last(explode('/',$response['image']));
        unset($this->params['image']);
        $productsCategoriesIdsStep2 = array_merge(
            [],
            $productCategories1->pluck('id')->toArray(),
            $this->productCategories->pluck('id')->toArray()
        );
        $this->params['categories'] = json_encode($productsCategoriesIdsStep2);
        $response2 = $this
            ->withHeaders(['Accept' => 'application/json'])
            ->post(route('management.products.update', $response['id']), $this->params);
        $response2->assertStatus(
            Response::HTTP_OK
        );
        $this->assertTrue(Storage::disk('public')->exists($this->pathToImages.$productImageName));
        $this->assertTrue(Storage::disk('public')->exists($this->pathToImagesMini.$productImageName));
        $this->assertDatabaseHas('products', [
            'id' => $response['id'],
            'name' => $this->params['name'],
            'composition' => $this->params['composition'],
            'manufacturer' => $this->params['manufacturer'],
            'description' => $this->params['description'],
            'product_unit' => $this->params['product_unit'],
        ]);
        $this->assertTrue(
            ProductCategory::query()
                ->where('product_id', $response['id'])
                ->count()
            ===
            $countCategories*2
        );

        $this->setUpParams();
        $productCategories3 = $this->productCategories;
        $indexesForGet = [0, $countCategories/2, $countCategories-1];
        $prepareProductCategories3 = [
            $productCategories1[$indexesForGet[0]]['id'],
            $productCategories1[$indexesForGet[1]]['id'],
            $productCategories1[$indexesForGet[2]]['id'],
            $productCategories2[$indexesForGet[0]]['id'],
            $productCategories2[$indexesForGet[1]]['id'],
            $productCategories2[$indexesForGet[2]]['id'],
            $productCategories3[$indexesForGet[0]]['id'],
            $productCategories3[$indexesForGet[1]]['id'],
            $productCategories3[$indexesForGet[2]]['id'],
        ];
        $this->params['categories'] = json_encode($prepareProductCategories3);
        $response3 = $this
            ->withHeaders(['Accept' => 'application/json'])
            ->post(route('management.products.update', $response['id']), $this->params);
        $response3->assertStatus(
            Response::HTTP_OK
        );
        $productImageName2 = last(explode('/',$response3['image']));
        $this->assertFalse(Storage::disk('public')->exists($this->pathToImages.$productImageName));
        $this->assertFalse(Storage::disk('public')->exists($this->pathToImagesMini.$productImageName));
        $this->assertTrue(Storage::disk('public')->exists($this->pathToImages.$productImageName2));
        $this->assertTrue(Storage::disk('public')->exists($this->pathToImagesMini.$productImageName2));
        $productCategoriesCollection = ProductCategory::query()
            ->where('product_id', $response['id'])
            ->get();
        $this->assertTrue($productCategoriesCollection->count() === count($prepareProductCategories3));
        foreach ($prepareProductCategories3 as $value) {
            $this->assertNotNull($productCategoriesCollection
                ->where('category_id', $value)
                ->where('product_id', $response['id'])
                ->firstOrFail()
            );
        }
        Storage::disk('public')->delete($this->pathToImages.$productImageName2);
        Storage::disk('public')->delete($this->pathToImagesMini.$productImageName2);
    }

    public function testDeleteProduct()
    {
        $response = $this
            ->withHeaders(['Accept' => 'application/json'])
            ->post(route('management.products.store'), $this->params);
        $response2 = $this
            ->withHeaders(['Accept' => 'application/json'])
            ->delete(route('management.products.destroy', $response['id']));
        $response2->assertStatus(
            Response::HTTP_OK
        );
        $this->assertDatabaseMissing('products', ['id' => $response['id']]);
        $this->assertTrue(
            ProductCategory::query()
                ->where('product_id', $response['id'])
                ->count()
            ===
            0
        );
        $productImageName = last(explode('/',$response['image']));
        $this->assertFalse(Storage::disk('public')->exists($this->pathToImages.$productImageName));
        $this->assertFalse(Storage::disk('public')->exists($this->pathToImagesMini.$productImageName));
    }

}
