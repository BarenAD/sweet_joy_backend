<?php
/**
 * Created by PhpStorm.
 * User: barenad
 * Date: 23.08.21
 * Time: 16:08
 */

namespace App\Http\Services;


use App\DTO\ProductDTO;
use App\Models\Product;
use App\Repositories\ProductCategoriesRepository;
use App\Repositories\ProductsRepository;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

/**
 * Class ItemsService
 * @package App\Http\Services
 */
class ProductsService
{
    private ProductsRepository $productsRepository;
    private ProductCategoriesRepository $productCategoriesRepository;
    private string $pathToImages;
    private string $pathToImagesMini;

    public function __construct(
        ProductsRepository $productsRepository,
        ProductCategoriesRepository $productCategoriesRepository
    ){
        $this->productsRepository = $productsRepository;
        $this->productCategoriesRepository = $productCategoriesRepository;
        $this->pathToImages = config('filesystems.path_inside_disk.products.images');
        $this->pathToImagesMini = config('filesystems.path_inside_disk.products.images_mini');
    }

    private function preparedProductPathToImages(Product &$product): void
    {
        $product['image_mini'] = Storage::disk('public')->url($this->pathToImagesMini.$product->image);
        $product['image'] = Storage::disk('public')->url($this->pathToImages.$product->image);
    }

    private function saveImage(string $imageName, UploadedFile $fileImage): void
    {
        $image = Image::make($fileImage);
        $image->resize(700, 700)->save();
        Storage::disk('public')->putFileAs($this->pathToImages, $image->basePath(), $imageName);
        $image->resize(200, 200)->save();
        Storage::disk('public')->putFileAs($this->pathToImagesMini, $image->basePath(), $imageName);
    }

    public function getAll(): array
    {
        $products = $this->productsRepository->getAll();
        $categories = $this->productCategoriesRepository->getAll()->groupBy('product_id');
        $result = [];

        foreach ($products as &$product) {
            $this->preparedProductPathToImages($product);
        }

        foreach ($products as $product)
        {
            $result[] = [
                'product' => $product->toArray(),
                'categories' => $categories[$product->id]->pluck('category_id')
            ];
        }

        return $result;
    }

    public function find($id)
    {
        $product = $this->productsRepository->find($id);
        $this->preparedProductPathToImages($product);
        return [
            'product' => $product->toArray(),
            'categories' => $this->productCategoriesRepository->getByProductId($product->id)->pluck('category_id')
        ];
    }

    public function store(ProductDTO $productDTO): array
    {
        $imageName = uniqid().'.jpg';
        try {
            $this->saveImage($imageName, $productDTO->image);
            DB::beginTransaction();
                $product = $this->productsRepository->store([
                    'image' => $imageName,
                    'name' => $productDTO->name,
                    'composition' => $productDTO->composition,
                    'manufacturer' => $productDTO->manufacturer,
                    'description' => $productDTO->description,
                    'product_unit' => $productDTO->product_unit
                ]);
                $categoriesForInsert = [];
                foreach ($productDTO->product_categories as $categoryId) {
                    $categoriesForInsert[] = [
                        'product_id' => $product->id,
                        'category_id' => $categoryId,
                    ];
                }
                $this->productCategoriesRepository->insert($categoriesForInsert);
                $this->preparedProductPathToImages($product);
            DB::commit();
            return [
                'product' => $product->toArray(),
                'categories' => $productDTO->product_categories
            ];
        } catch (\Throwable $error) {
            DB::rollBack();
            Storage::disk('public')->delete($this->pathToImages.$imageName);
            Storage::disk('public')->delete($this->pathToImagesMini.$imageName);
            throw $error;
        }
    }

    public function update(int $id, ProductDTO $productDTO): array
    {
        try {
            DB::beginTransaction();
            $product = $this->productsRepository->update($id, [
                'name' => $productDTO->name,
                'composition' => $productDTO->composition,
                'manufacturer' => $productDTO->manufacturer,
                'description' => $productDTO->description,
                'product_unit' => $productDTO->product_unit
            ]);
            $categories = $this->productCategoriesRepository->getByProductId($id);
            foreach ($categories->whereNotIn('category_id', $productDTO->product_categories) as $category) {
                $category->delete();
            }
            $diffCategoriesId = array_diff(
                $productDTO->product_categories,
                $categories->pluck('category_id')->toArray()
            );
            $categoriesForInsert = [];
            foreach ($diffCategoriesId as $categoryId) {
                $categoriesForInsert[] = [
                    'product_id' => $product->id,
                    'category_id' => $categoryId,
                ];
            }
            $this->productCategoriesRepository->insert($categoriesForInsert);
            if (!is_null($productDTO->image)) {
                $this->saveImage($product->image, $productDTO->image);
            }
            $this->preparedProductPathToImages($product);
            DB::commit();
            return [
                'product' => $product->toArray(),
                'categories' => $productDTO->product_categories
            ];
        } catch (\Throwable $error) {
            DB::rollBack();
            throw $error;
        }
    }

    public function destroy(int $id): int
    {
        $product = $this->productsRepository->find($id);
        Storage::disk('public')->delete($this->pathToImages.$product->image);
        Storage::disk('public')->delete($this->pathToImagesMini.$product->image);
        return $product->delete();
    }
}