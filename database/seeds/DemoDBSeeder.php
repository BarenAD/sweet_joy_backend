<?php

use App\Models\Category;
use App\Models\Operator;
use App\Models\ProductCategory;
use App\Models\Product;
use App\Models\Schedule;
use App\Models\Shop;
use App\Models\ShopProduct;
use Illuminate\Database\Seeder;
use App\Models\User;

class DemoDBSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (env('APP_DEMO_MODE', false)) {
            $newAdmin = User::create([
                'fio' => 'Админов Админ Админович',
                'login' => 'admin',
                'password' => bcrypt('admin'),
                'email' => 'admin@demo.com',
                'phone' => '70000000000'
            ]);
            Operator::create([
                'user_id' => $newAdmin->id
            ]);
            $categories = [
                Category::create(['name' => 'Конфеты']),
                Category::create(['name' => 'Подарочные наборы']),
                Category::create(['name' => 'Без сахара']),
                Category::create(['name' => 'Экологичная упаковка']),
                Category::create(['name' => 'Фастфуд']),
                Category::create(['name' => 'Без глютена']),
            ];
            $schedules = [
                Schedule::create([
                    'name' => 'Обычный режим работы',
                    'monday' => 'С 9:00 до 18:00. Обед с 13:00 до 14:00.',
                    'tuesday' => 'С 9:00 до 18:00. Обед с 13:00 до 14:00.',
                    'wednesday' => 'С 9:00 до 18:00. Обед с 13:00 до 14:00.',
                    'thursday' => 'С 9:00 до 18:00. Обед с 13:00 до 14:00.',
                    'friday' => 'С 9:00 до 18:00. Обед с 13:00 до 14:00.',
                    'saturday' => 'С 9:00 до 18:00. Обед с 13:00 до 14:00.',
                    'sunday' => 'С 10:00 до 14:00. Без перерывов.',
                    'holiday' => 'С 10:00 до 14:00. Без перерывов.',
                    'particular' => 'С 10:00 до 14:00. Без перерывов.'
                ]),
                Schedule::create([
                    'name' => 'Карантинный режим работы',
                    'monday' => 'С 9:00 до 18:00. Обед с 13:00 до 14:00. Наличие маски обязательно.',
                    'tuesday' => 'С 9:00 до 18:00. Обед с 13:00 до 14:00. Наличие маски обязательно.',
                    'wednesday' => 'С 9:00 до 18:00. Обед с 13:00 до 14:00. Наличие маски обязательно.',
                    'thursday' => 'С 9:00 до 18:00. Обед с 13:00 до 14:00. Наличие маски обязательно.',
                    'friday' => 'С 9:00 до 18:00. Обед с 13:00 до 14:00. Наличие маски обязательно.',
                    'saturday' => 'С 9:00 до 18:00. Обед с 13:00 до 14:00. Наличие маски обязательно.',
                    'sunday' => 'Выходной',
                    'holiday' => 'Выходной',
                    'particular' => 'Выходной'
                ])
            ];
            $shops = [
                Shop::create([
                    'address' => 'Ленина 120, корпус 46',
                    'phone' => '79130000202',
                    'schedule_id' => $schedules[0]->id,
                    'map_integration' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d916.9619145338502!2d37.60946573413041!3d55.76161982703659!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x46b54a443a83ac07%3A0xadf75d017913401c!2z0J_QsNC80Y_RgtC90LjQuiDQrtGA0LjRjiDQlNC-0LvQs9C-0YDRg9C60L7QvNGD!5e0!3m2!1sru!2sru!4v1630992435798!5m2!1sru!2sru" width="800" height="600" style="border:0;" allowfullscreen="" loading="lazy"></iframe>'
                ]),
                Shop::create([
                    'address' => 'Суворова 36/1',
                    'phone' => '79030000201',
                    'schedule_id' => $schedules[1]->id,
                    'map_integration' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1587.9195119691037!2d37.641745010155795!3d55.747089465302615!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x46b54af0623b063f%3A0xa526e27d4e68d917!2z0JLRi9GB0L7RgtC60LAg0L3QsCDQmtC-0YLQtdC70YzQvdC40YfQtdGB0LrQvtC5INC90LDQsdC10YDQtdC20L3QvtC5!5e0!3m2!1sru!2sru!4v1630992565070!5m2!1sru!2sru" width="800" height="600" style="border:0;" allowfullscreen="" loading="lazy"></iframe>',
                ]),
                Shop::create([
                    'address' => 'Новогодняя 3',
                    'phone' => '70000000209',
                    'schedule_id' => $schedules[0]->id,
                    'map_integration' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3664.8822000959376!2d37.67746707203464!3d55.79313763771376!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x46b535b7788c0391%3A0x1dbb7567c7495d08!2z0J_QsNGA0Log0KHQvtC60L7Qu9GM0L3QuNC60Lg!5e0!3m2!1sru!2sru!4v1630992589647!5m2!1sru!2sru" width="800" height="600" style="border:0;" allowfullscreen="" loading="lazy"></iframe>',
                ]),
            ];
            $products = [
                Product::create([
                    'image' => 'demo_cat.jpg',
                    'name' => 'Демо товар 1',
                    'composition' => 'Только лучшие и отброные мемы',
                    'manufacturer' => 'BarenAD industries',
                    'description' => 'Отлично демонстрирует возможности',
                    'product_unit' => 'фасовка 100г/300г'
                ]),
                Product::create([
                    'image' => 'demo_cubes.jpg',
                    'name' => 'Демо товар 2',
                    'composition' => 'Только лучшие и отброные мемы',
                    'manufacturer' => 'BarenAD industries',
                    'description' => 'Отлично демонстрирует возможности',
                    'product_unit' => 'поштучно'
                ])
            ];
            ProductCategory::insert([
                [
                    'product_id' => $products[0]->id,
                    'category_id' => $categories[0]->id
                ],
                [
                    'product_id' => $products[0]->id,
                    'category_id' => $categories[1]->id
                ],
                [
                    'product_id' => $products[0]->id,
                    'category_id' => $categories[2]->id
                ],
                [
                    'product_id' => $products[1]->id,
                    'category_id' => $categories[3]->id
                ],
                [
                    'product_id' => $products[1]->id,
                    'category_id' => $categories[4]->id
                ],
                [
                    'product_id' => $products[1]->id,
                    'category_id' => $categories[5]->id
                ],
            ]);
            ShopProduct::insert([
                [
                    'price' => 59,
                    'count' => 36,
                    'product_id' =>  $products[0]->id,
                    'shop_id' => $shops[0]->id,
                ],
                [
                    'price' => 79,
                    'count' => 60,
                    'product_id' =>  $products[0]->id,
                    'shop_id' => $shops[1]->id,
                ],
                [
                    'price' => 54,
                    'count' => 87,
                    'product_id' =>  $products[0]->id,
                    'shop_id' => $shops[2]->id,
                ],
                [
                    'price' => 120,
                    'count' => 8,
                    'product_id' =>  $products[1]->id,
                    'shop_id' => $shops[0]->id,
                ],
                [
                    'price' => 150,
                    'count' => 24,
                    'product_id' =>  $products[1]->id,
                    'shop_id' => $shops[2]->id,
                ],
            ]);
        }
    }
}
