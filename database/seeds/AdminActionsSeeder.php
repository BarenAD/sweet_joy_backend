<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use App\AdminAction;

class AdminActionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AdminAction::insert([
            [
                'id' => 1,
                'name' => 'Управление администраторами',
                'description' => 'Назначение ролей администрирования пользователям.',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'id' => 2,
                'name' => 'Управление товарами',
                'description' => 'Управление товарами в точках продаж.',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'id' => 3,
                'name' => 'Управление точками продаж',
                'description' => 'Создание новых, удаление, редактирование, назначение расписаний',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
        ]);
    }
}
