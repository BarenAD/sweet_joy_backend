<?php

use App\Models\Category;
use App\Models\Document;
use App\Models\DocumentLocation;
use App\Models\Operator;
use App\Models\ProductCategory;
use App\Models\Product;
use App\Models\Schedule;
use App\Models\Shop;
use App\Models\ShopProduct;
use App\Models\SiteConfiguration;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DemoDBSeeder extends Seeder
{

    private int $multiplicity = 25;

    public int $count;

    public Collection $categories;
    public Collection $products;
    public Collection $productCategories;
    public Collection $schedules;
    public Collection $shops;
    public Collection $shopProducts;
    public Collection $documents;
    public Collection $documentLocations;
    public Collection $siteConfigurations;
    public array $userAttributes;

    public function __construct()
    {
        $this->categories = new Collection();
        $this->products = new Collection();
        $this->productCategories = new Collection();
        $this->schedules = new Collection();
        $this->shops = new Collection();
        $this->shopProducts = new Collection();
        $this->documents = new Collection();
        $this->documentLocations = new Collection();
        $this->siteConfigurations = new Collection();
        $this->count = 6 * $this->multiplicity;
        $this->userAttributes = [
            'fio' => 'Админов Админ Админович',
            'phone' => '70000000000',
            'email' => env('DEMO_USER_EMAIL', 'admin@admin.ru'),
            'password' => env('DEMO_USER_PASSWORD', 'qwerty'),
        ];
    }

    public function seedCategories()
    {
        $this->categories = $this->categories
            ->merge(
                Category::factory()
                    ->count($this->count)
                    ->create()
            )
            ->keyBy('id');
    }

    public function seedProducts()
    {
        $newProducts = Product::factory()
            ->count($this->count)
            ->create()
            ->keyBy('id');
        $this->products = $this->products
            ->merge($newProducts)
            ->keyBy('id');
        $this->seedProductCategories($newProducts);
    }

    private function seedProductCategories(Collection $products)
    {
        $productCategories = new Collection();
        foreach ($products as $productId => $product) {
            $categoryIds = array_rand($this->categories->toArray(), $this->count / 2);
            foreach ($categoryIds as $categoryId) {
                $productCategories->push(
                    ProductCategory::factory([
                        'product_id' => $productId,
                        'category_id' => $categoryId,
                    ])->create()
                );
            }
        }
        $this->productCategories = $this->productCategories->merge($productCategories);
    }

    public function seedSchedules()
    {
        $this->schedules = $this->schedules
            ->merge(
                Schedule::factory()
                    ->count(ceil($this->count / 25))
                    ->create()
            )
            ->keyBy('id');
    }

    public function seedShops()
    {
        $newShops = new Collection();
        for($i = 0; $i < ceil($this->count / 25); $i++) {
            $newShops->push(
                Shop::factory([
                    'schedule_id' => $this->schedules->random()['id'],
                ])->create()
            );
        }
        $newShops = $newShops->keyBy('id');
        $this->shops = $this->shops
            ->merge($newShops)
            ->keyBy('id');
        $this->seedShopProducts($newShops);
    }

    private function seedShopProducts(Collection $shops)
    {
        foreach ($shops as $shopId => $shop) {
            $productIds = array_rand($this->products->toArray(), $this->count);
            foreach ($productIds as $productId) {
                $this->shopProducts->push(
                    ShopProduct::factory([
                        'shop_id' => $shopId,
                        'product_id' => $productId
                    ])->create()
                );
            }
        }
    }

    public function seedDocuments()
    {
        $this->documents = $this->documents
            ->merge(
                Document::factory()
                    ->count($this->count)
                    ->create()
            )
            ->keyBy('id');
    }

    public function seedDocumentLocations()
    {
        $seeder = new DocumentLocationSeeder();
        $seeder->run();
        $this->documentLocations = DocumentLocation::all()->keyBy('id');
        foreach ($this->documentLocations as $documentLocation) {
            $documentLocation->document_id = $this->documents->random()['id'];
            $documentLocation->save();
        }
    }

    public function seedSiteConfigurations()
    {
        $seeder = new SiteConfigurationSeeder();
        $seeder->run();
        $this->siteConfigurations = SiteConfiguration::all()->keyBy('identify');
        $defaultValue = 'BARENAD';
        $headerLastContent = '<span>Демонстрационный проект</span><span>Автор: Малашин П.С.</span><span>'.$defaultValue.'</span>';
        foreach ($this->siteConfigurations as $identify => $siteConfiguration) {
            switch ($identify) {
                case SiteConfiguration::HEADER_LAST:
                    $siteConfiguration->value = $headerLastContent;
                    break;
                case SiteConfiguration::FOOTER_FIRST:
                    $siteConfiguration->value = env('AUTHOR_VK', $defaultValue);
                    break;
                case SiteConfiguration::FOOTER_SECOND:
                    $siteConfiguration->value = env('AUTHOR_SUMMARY', $defaultValue);
                    break;
                case SiteConfiguration::FOOTER_THIRD:
                    $siteConfiguration->value = env('AUTHOR_TELEGRAMM', $defaultValue);
                    break;
                default:
                    $siteConfiguration->value = $defaultValue;
                    break;
            }
            $siteConfiguration->save();
        }
    }

    public function handleSeed()
    {
        $this->seedCategories();
        $this->seedCategories();
        $this->seedProducts();
        $this->seedProducts();
        $this->seedSchedules();
        $this->seedSchedules();
        $this->seedShops();
        $this->seedDocuments();
        $this->seedDocumentLocations();
        $this->seedSiteConfigurations();
        try {
            $user = User::query()->create($this->userAttributes);
            Operator::query()->create([
                'user_id' => $user->id,
            ]);
        } catch (\Throwable $exception) {

        }
    }

    public function run()
    {
        if (env('APP_DEMO_MODE', false)) {
            $this->handleSeed();
        }
    }
}
