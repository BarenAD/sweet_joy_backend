<?php


namespace App\Repositories;


use App\Models\DocumentLocation;

/**
 * Class LocationsDocumentsRepository
 * @package App\Repositories
 */
class DocumentLocationRepository extends CoreRepository
{
    public function getModelClass(): string
    {
        return DocumentLocation::class;
    }
}
