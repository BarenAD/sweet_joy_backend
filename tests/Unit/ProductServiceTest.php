<?php


namespace Tests\Unit;


use App\DTO\ProductDTO;
use App\Exceptions\NoReportException;
use App\Http\Services\ProductService;
use App\Models\Product;
use App\Repositories\ProductCategoryRepository;
use App\Repositories\ProductRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Mockery\MockInterface;
use Tests\TestCase;

class ProductServiceTest extends TestCase
{
    use RefreshDatabase;

    private string $pathToImages;
    private string $pathToImagesMini;
    private ProductDTO $productDto;

    public function setUp(): void
    {
        parent::setUp();
        $this->pathToImages = config('filesystems.path_inside_disk.products.images');
        $this->pathToImagesMini = config('filesystems.path_inside_disk.products.images_mini');
        $uploadFile = UploadedFile::fake()->image('test.jpg', 1000, 1000);
        $this->productDto = ProductDTO::make([
            'image' => $uploadFile,
            'name' => 'test',
            'composition' => 'test',
            'manufacturer' => 'test',
            'description' => 'test',
            'product_unit' => 'test',
            'categories' => [],
        ]);
    }

    public function testStore()
    {
        $productService = app()->make(ProductService::class);
        $productObject = $productService->store($this->productDto);
        $productImageName = last(explode('/',$productObject['image']));
        $productObject['image'] = $productImageName;
        unset($productObject['categories']);
        unset($productObject['image_mini']);
        $this->assertDatabaseHas('products', $productObject);
        $this->assertTrue(
            Storage::disk('public')
                ->exists($this->pathToImages . $productImageName)
        );
        $this->assertTrue(
            Storage::disk('public')
                ->exists($this->pathToImagesMini . $productImageName)
        );
        Storage::disk('public')->delete($this->pathToImages . $productImageName);
        Storage::disk('public')->delete($this->pathToImagesMini . $productImageName);
    }

    public function testStoreException()
    {
        $this->mock(
            ProductRepository::class,
            function (MockInterface $mock) {
                $mock->shouldReceive('store')->andThrowExceptions([
                    new NoReportException('test'),
                ]);
            }
        );
        $productService = app()->make(ProductService::class);
        $countFilesBeforeTryStore = [
            count(Storage::disk('public')->allFiles($this->pathToImages)),
            count(Storage::disk('public')->allFiles($this->pathToImagesMini)),
        ];
        try {
            $productService->store($this->productDto);
            $this->markTestIncomplete();
        } catch (\Throwable $exception) {
            $this->assertTrue($exception->getMessage() === config('exceptions.file_is_not_stored.message'));
            $countFilesAfterTryStore = [
                count(Storage::disk('public')->allFiles($this->pathToImages)),
                count(Storage::disk('public')->allFiles($this->pathToImagesMini)),
            ];
            $this->assertTrue($countFilesBeforeTryStore[0] === $countFilesAfterTryStore[0]);
            $this->assertTrue($countFilesBeforeTryStore[1] === $countFilesAfterTryStore[1]);
        }
    }

    public function testUpdateException()
    {
        $productService = app()->make(ProductService::class);
        $productObjectBeforeUpdate = $productService->store($this->productDto);
        $this->mock(
            ProductCategoryRepository::class,
            function (MockInterface $mock) {
                $mock->shouldReceive('insert')
                    ->andThrow(
                        new NoReportException('test')
                    );
            }
        );
        $productService = app()->make(ProductService::class);
        $productImageNameBeforeUpdate = last(explode('/',$productObjectBeforeUpdate['image']));
        $countFilesBeforeTryUpdate = [
            count(Storage::disk('public')->allFiles($this->pathToImages)),
            count(Storage::disk('public')->allFiles($this->pathToImagesMini)),
        ];
        $uploadFile = UploadedFile::fake()->image('test2.jpg', 1000, 1000);
        $newDto = ProductDTO::make([
            'image' => $uploadFile,
            'name' => 'test2',
            'composition' => 'test2',
            'manufacturer' => 'test2',
            'description' => 'test2',
            'product_unit' => 'test2',
            'categories' => [],
        ]);
        try {
            $productService->update($productObjectBeforeUpdate['id'], $newDto);
            $this->markTestIncomplete();
        } catch (\Throwable $exception) {
            $this->assertTrue($exception->getMessage() === config('exceptions.file_is_not_update.message'));
            $countFilesAfterTryUpdate = [
                count(Storage::disk('public')->allFiles($this->pathToImages)),
                count(Storage::disk('public')->allFiles($this->pathToImagesMini)),
            ];
            $this->assertTrue($countFilesBeforeTryUpdate[0] === $countFilesAfterTryUpdate[0]);
            $this->assertTrue($countFilesBeforeTryUpdate[1] === $countFilesAfterTryUpdate[1]);
            $this->assertTrue(
                Storage::disk('public')
                    ->exists($this->pathToImages . $productImageNameBeforeUpdate)
            );
            $this->assertTrue(
                Storage::disk('public')
                    ->exists($this->pathToImagesMini . $productImageNameBeforeUpdate)
            );
            Storage::disk('public')->delete($this->pathToImages . $productImageNameBeforeUpdate);
            Storage::disk('public')->delete($this->pathToImagesMini . $productImageNameBeforeUpdate);
        }
    }

    public function testDestroyException()
    {
        $productService = app()->make(ProductService::class);
        $productObjectBeforeDestroy = $productService->store($this->productDto);
        $productImageNameBeforeDestroy = last(explode('/',$productObjectBeforeDestroy['image']));
        $this->mock(
            Product::class,
            function (MockInterface $mock) {
                $mock->shouldReceive('delete')->andThrowExceptions([
                    new NoReportException('test'),
                ]);
            }
        );
        $productService = app()->make(ProductService::class);
        try {
            $productService->destroy($productObjectBeforeDestroy['id']);
            $this->markTestIncomplete();
        } catch (\Throwable $exception) {
            $productObjectBeforeDestroy['image'] = $productImageNameBeforeDestroy;
            unset($productObjectBeforeDestroy['categories']);
            unset($productObjectBeforeDestroy['image_mini']);
            $this->assertDatabaseHas('products', $productObjectBeforeDestroy);
            $this->assertTrue($exception->getMessage() === config('exceptions.file_is_not_destroy.message'));
            $this->assertTrue(
                Storage::disk('public')
                    ->exists($this->pathToImages . $productImageNameBeforeDestroy)
            );
            $this->assertTrue(
                Storage::disk('public')
                    ->exists($this->pathToImagesMini . $productImageNameBeforeDestroy)
            );
        }
        Storage::disk('public')->delete($this->pathToImages . $productImageNameBeforeDestroy);
        Storage::disk('public')->delete($this->pathToImagesMini . $productImageNameBeforeDestroy);
    }

    public function testDestroyByDemo() {
        $productService = app()->make(ProductService::class);
        $pathToImages = config('filesystems.path_inside_disk.products.images');
        $pathToImagesMini = config('filesystems.path_inside_disk.products.images_mini');
        $product = Product::factory()->create();
        $productService->destroy($product->id);
        $this->assertTrue(
            Storage::disk('public')
                ->exists($pathToImages . $product->image)
        );
        $this->assertTrue(
            Storage::disk('public')
                ->exists($pathToImagesMini . $product->image)
        );
    }
}
