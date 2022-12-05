<?php

namespace Database\Factories;

use App\Models\DocumentLocation;

class DocumentLocationFactory extends CoreFactory
{
    protected $model = DocumentLocation::class;

    public function definition()
    {
        return $this->decorateTimestamp([
            'name' => $this->faker->text(100),
            'identify' => uniqid('identify_'),
            'document_id' => null,
        ]);
    }
}
