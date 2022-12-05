<?php

namespace App\DTO;

use Illuminate\Database\Eloquent\Model;
use Spatie\DataTransferObject\DataTransferObject;

class ParentModelDTO extends DataTransferObject
{
    public Model $model;
    public string $foreignKey;
    public bool $needInRoute;

    public static function make($params): self
    {
        return new self([
            'model' => $params['model'],
            'foreignKey' => $params['foreignKey'],
            'needInRoute' => $params['needInRoute'] ?? false,
        ]);
    }
}
