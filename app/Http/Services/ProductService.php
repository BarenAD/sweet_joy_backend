<?php
/**
 * Created by PhpStorm.
 * User: barenad
 * Date: 23.08.21
 * Time: 16:08
 */

namespace App\Http\Services;


use App\DTO\ProductDTO;
use App\Exceptions\BaseException;
use App\Models\Product;
use App\Repositories\ProductCategoryRepository;
use App\Repositories\ProductRepository;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

/**
 * Class ItemsService
 * @package App\Http\Services
 */
class ProductService
{
    private ProductRepository $productsRepository;
    private ProductCategoryRepository $productCategoriesRepository;
    private string $pathToImages;
    private string $pathToImagesMini;

    public function __construct(
        ProductRepository $productsRepository,
        ProductCategoryRepository $productCategoriesRepository
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

    private function deleteImage(string $imageName): void {
        Storage::disk('public')->delete($this->pathToImages.$imageName);
        Storage::disk('public')->delete($this->pathToImagesMini.$imageName);
    }

    public function getAll(): array
    {
        $products = $this->productsRepository->getAll();
        $categories = $this->productCategoriesRepository->getAll()->groupBy('product_id');
        foreach ($products as &$product) {
            $this->preparedProductPathToImages($product);
            $product['categories'] = isset($categories[$product['id']]) ?
                $categories[$product['id']]->pluck('category_id')
                :
                [];
        }

        return $products->toArray();
    }

    public function find($id)
    {
        $product = $this->productsRepository->find($id);
        $this->preparedProductPathToImages($product);
        $product['categories'] = $this->productCategoriesRepository->getByProductId($product->id)->pluck('category_id');
        return $product->toArray();
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
                foreach ($productDTO->categories as $categoryId) {
                    $categoriesForInsert[] = [
                        'product_id' => $product->id,
                        'category_id' => $categoryId,
                    ];
                }
                $this->productCategoriesRepository->insert($categoriesForInsert);
                $this->preparedProductPathToImages($product);
            DB::commit();
            $product['categories'] = $productDTO->categories;
            return $product->toArray();
        } catch (\Throwable $exception) {
            DB::rollBack();
            Storage::disk('public')->delete($this->pathToImages.$imageName);
            Storage::disk('public')->delete($this->pathToImagesMini.$imageName);
            throw new BaseException('file_is_not_stored', $exception);
        }
    }

    public function update(int $id, ProductDTO $productDTO): array
    {
        $newNameImages = null;
        try {
            DB::beginTransaction();
            $product = $this->productsRepository->find($id);
            $oldImageName = $product['image'];
            if (!is_null($productDTO->image)) {
                $product['image'] = uniqid().'.jpg';
                $newNameImages = $product['image'];
                $this->saveImage($product['image'], $productDTO->image);
            }
            $product['name'] = $productDTO->name;
            $product['composition'] = $productDTO->composition;
            $product['manufacturer'] = $productDTO->manufacturer;
            $product['description'] = $productDTO->description;
            $product['product_unit'] = $productDTO->product_unit;
            $categories = $this->productCategoriesRepository->getByProductId($id);
            foreach ($categories->whereNotIn('category_id', $productDTO->categories) as $category) {
                $category->delete();
            }
            $diffCategoriesId = array_diff(
                $productDTO->categories,
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
            $product->save();
            if ($oldImageName !== $product['image']) {
                $this->deleteImage($oldImageName);
            }
            $this->preparedProductPathToImages($product);
            DB::commit();
            $product['categories'] = $productDTO->categories;
            return $product->toArray();
        } catch (\Throwable $exception) {
            DB::rollBack();
            if (!is_null($newNameImages)) {
                $this->deleteImage($newNameImages);
            }
            throw new BaseException('file_is_not_update', $exception);
        }
    }

    public function destroy(int $id): int
    {
        try {
            DB::beginTransaction();
            $product = $this->productsRepository->find($id);
            $result = $product->delete();
            Storage::disk('public')->delete($this->pathToImages.$product->image);
            Storage::disk('public')->delete($this->pathToImagesMini.$product->image);
            DB::commit();
            return $result;
        } catch (\Throwable $exception) {
            DB::rollBack();
            throw new BaseException('file_is_not_destroy', $exception);
        }
    }
}
