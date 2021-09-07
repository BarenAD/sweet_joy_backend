<?php

use App\Models\CategoryItem;
use App\Models\InformationCategorieItem;
use App\Models\Item;
use App\Models\PointOfSale;
use App\Models\ProductInformation;
use App\Models\Schedule;
use Illuminate\Database\Seeder;
use App\Models\SuperAdmin;
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
            SuperAdmin::create([
                'id_u' => $newAdmin->id
            ]);
            $categories = [
                CategoryItem::create(['name' => 'Конфеты']),
                CategoryItem::create(['name' => 'Подарочные наборы']),
                CategoryItem::create(['name' => 'Без сахара']),
                CategoryItem::create(['name' => 'Экологичная упаковка']),
                CategoryItem::create(['name' => 'Фастфуд']),
                CategoryItem::create(['name' => 'Без глютена']),
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
            $pointsOfSale = [
                PointOfSale::create([
                    'address' => 'Ленина 120, корпус 46',
                    'phone' => '79130000202',
                    'id_s' => $schedules[0]->id,
                    'map_integration' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d916.9619145338502!2d37.60946573413041!3d55.76161982703659!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x46b54a443a83ac07%3A0xadf75d017913401c!2z0J_QsNC80Y_RgtC90LjQuiDQrtGA0LjRjiDQlNC-0LvQs9C-0YDRg9C60L7QvNGD!5e0!3m2!1sru!2sru!4v1630992435798!5m2!1sru!2sru" width="800" height="600" style="border:0;" allowfullscreen="" loading="lazy"></iframe>'
                ]),
                PointOfSale::create([
                    'address' => 'Суворова 36/1',
                    'phone' => '79030000201',
                    'id_s' => $schedules[1]->id,
                    'map_integration' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1587.9195119691037!2d37.641745010155795!3d55.747089465302615!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x46b54af0623b063f%3A0xa526e27d4e68d917!2z0JLRi9GB0L7RgtC60LAg0L3QsCDQmtC-0YLQtdC70YzQvdC40YfQtdGB0LrQvtC5INC90LDQsdC10YDQtdC20L3QvtC5!5e0!3m2!1sru!2sru!4v1630992565070!5m2!1sru!2sru" width="800" height="600" style="border:0;" allowfullscreen="" loading="lazy"></iframe>',
                ]),
                PointOfSale::create([
                    'address' => 'Новогодняя 3',
                    'phone' => '70000000209',
                    'id_s' => $schedules[0]->id,
                    'map_integration' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3664.8822000959376!2d37.67746707203464!3d55.79313763771376!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x46b535b7788c0391%3A0x1dbb7567c7495d08!2z0J_QsNGA0Log0KHQvtC60L7Qu9GM0L3QuNC60Lg!5e0!3m2!1sru!2sru!4v1630992589647!5m2!1sru!2sru" width="800" height="600" style="border:0;" allowfullscreen="" loading="lazy"></iframe>',
                ]),
            ];
            $items = [
                Item::create([
                    'picture' => 'static_from_server/images/demo_cat.jpg',
                    'miniature_picture' => 'static_from_server/images/mini/demo_cat.jpg',
                    'name' => 'Демо товар 1',
                    'composition' => 'Только лучшие и отброные мемы',
                    'manufacturer' => 'BarenAD industries',
                    'description' => 'Отлично демонстрирует возможности',
                    'product_unit' => 'фасовка 100г/300г'
                ]),
                Item::create([
                    'picture' => 'static_from_server/images/demo_cubes.jpg',
                    'miniature_picture' => 'static_from_server/images/mini/demo_cubes.jpg',
                    'name' => 'Демо товар 2',
                    'composition' => 'Только лучшие и отброные мемы',
                    'manufacturer' => 'BarenAD industries',
                    'description' => 'Отлично демонстрирует возможности',
                    'product_unit' => 'поштучно'
                ])
            ];
            InformationCategorieItem::insert([
                [
                    'id_i' => $items[0]->id,
                    'id_ci' => $categories[0]->id
                ],
                [
                    'id_i' => $items[0]->id,
                    'id_ci' => $categories[1]->id
                ],
                [
                    'id_i' => $items[0]->id,
                    'id_ci' => $categories[2]->id
                ],
                [
                    'id_i' => $items[1]->id,
                    'id_ci' => $categories[3]->id
                ],
                [
                    'id_i' => $items[1]->id,
                    'id_ci' => $categories[4]->id
                ],
                [
                    'id_i' => $items[1]->id,
                    'id_ci' => $categories[5]->id
                ],
            ]);
            ProductInformation::insert([
                [
                    'price' => 59,
                    'count' => 36,
                    'id_i' =>  $items[0]->id,
                    'id_pos' => $pointsOfSale[0]->id,
                ],
                [
                    'price' => 79,
                    'count' => 60,
                    'id_i' =>  $items[0]->id,
                    'id_pos' => $pointsOfSale[1]->id,
                ],
                [
                    'price' => 54,
                    'count' => 87,
                    'id_i' =>  $items[0]->id,
                    'id_pos' => $pointsOfSale[2]->id,
                ],
                [
                    'price' => 120,
                    'count' => 8,
                    'id_i' =>  $items[1]->id,
                    'id_pos' => $pointsOfSale[0]->id,
                ],
                [
                    'price' => 150,
                    'count' => 24,
                    'id_i' =>  $items[1]->id,
                    'id_pos' => $pointsOfSale[2]->id,
                ],
            ]);
        }
    }
}
