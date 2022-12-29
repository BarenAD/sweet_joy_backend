<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
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

    public function testIndexWithNotProduct()
    {
        $response = $this
            ->withHeaders(['Accept' => 'application/json'])
            ->get(route('management.products.categories.indexWithNotProduct'));
        $response->assertStatus(
            Response::HTTP_OK
        );
        $this->assertEquals($response->json(), $this->productCategories);
    }

    public function testIndexWithNotProductByProducts()
    {
        $response = $this
            ->withHeaders(['Accept' => 'application/json'])
            ->get(route('management.products.categories.indexWithNotProduct').'?groupBy=products');
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

    public function testIndexWithNotProductByCategories()
    {
        $response = $this
            ->withHeaders(['Accept' => 'application/json'])
            ->get(route('management.products.categories.indexWithNotProduct').'?groupBy=categories');
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
