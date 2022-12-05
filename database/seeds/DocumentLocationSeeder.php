<?php

use App\Models\DocumentLocation;
use Illuminate\Database\Seeder;

class DocumentLocationSeeder extends Seeder
{
    private array $inserting = [
        [
            'id' => 1,
            'name' => 'документ в верхнем баре на главной',
            'identify' => 'main_top_bar_document',
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->inserting as $params) {
            $newModel = new DocumentLocation($params);
            if ($newModel->exists()) {
                $newModel->update();
            } else {
                $newModel->save();
            }
        }
    }
}
