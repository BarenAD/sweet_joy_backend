<?php

namespace Tests\Feature;

use App\Exceptions\NoReportException;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Repositories\CategoryRepository;
use App\Repositories\ProductCategoryRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Mockery\MockInterface;
use Tests\TestCase;
use Tests\Traits\WithoutPermissionsTrait;

class ProductCategoryTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    use WithoutMiddleware;
    use WithoutPermissionsTrait;

    private Collection $products;
    private Collection $categories;
    private array $productCategories;

    public function setUp(): void {
        parent::setUp();
        $this->products = Product::factory()->count(10)->create();
        $this->categories = Category::factory()->count(30)->create();
        $this->productCategories = [];
        foreach ($this->products as $product) {
            foreach ($this->categories as $category) {
                $this->productCategories[] = ProductCategory::factory([
                    'product_id' => $product->id,
                    'category_id' => $category->id,
                ])
                    ->create()
                    ->toArray();
            }
        }
    }

    public function testGetAllWithCache()
    {
        $prepared = [];
        foreach ($this->productCategories as $item) {
            if (!isset($prepared[$item['product_id']])) {
                $prepared[$item['product_id']] = [];
            }
            $prepared[$item['product_id']][] = $item;
        }
        $response = $this
            ->withHeaders(['Accept' => 'application/json'])
            ->get(route('products.categories.getAll').'?groupBy=products&withCache=true');
        $response->assertStatus(
            Response::HTTP_OK
        );
        $this->assertEquals($response->json(), $prepared);
        $this->assertEquals(
            Cache::tags(['main_data', 'product_categories'])
                ->get('cache_product_categories_group_products', null),
            $prepared
        );
        $this->mock(
            ProductCategoryRepository::class,
            function (MockInterface $mock) {
                $mock->shouldReceive('getAll')->andThrowExceptions([
                    new NoReportException('test'),
                ]);
            }
        );
        $response2 = $this
            ->withHeaders(['Accept' => 'application/json'])
            ->get(route('products.categories.getAll').'?groupBy=products&withCache=true');
        $response2->assertStatus(
            Response::HTTP_OK
        );
        $this->assertEquals($response2->json(), $prepared);
    }

    public function testGetAllGroupByProducts()
    {
        $response = $this
            ->withHeaders(['Accept' => 'application/json'])
            ->get(route('products.categories.getAll').'?groupBy=products');
        $response->assertStatus(
            Response::HTTP_OK
        );
        $prepared = [];
        foreach ($this->productCategories as $item) {
            if (!isset($prepared[$item['product_id']])) {
                $prepared[$item['product_id']] = [];
            }
            $prepared[$item['product_id']][] = $item;
        }
        $this->assertEquals($response->json(), $prepared);
    }

    public function testGetAllGroupByCategories()
    {
        $response = $this
            ->withHeaders(['Accept' => 'application/json'])
            ->get(route('products.categories.getAll').'?groupBy=categories');
        $response->assertStatus(
            Response::HTTP_OK
        );
        $prepared = [];
        foreach ($this->productCategories as $item) {
            if (!isset($prepared[$item['category_id']])) {
                $prepared[$item['category_id']] = [];
            }
            $prepared[$item['category_id']][] = $item;
        }
        $this->assertEquals($response->json(), $prepared);
    }
}
