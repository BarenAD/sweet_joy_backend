<?php

namespace Database\Factories;

use App\Models\Document;

class DocumentFactory extends CoreFactory
{
    protected $model = Document::class;

    public function definition()
    {
        return $this->decorateTimestamp([
            'name' => $this->faker->text(100),
            'urn' => uniqid() . '.pdf',
        ]);
    }
}
