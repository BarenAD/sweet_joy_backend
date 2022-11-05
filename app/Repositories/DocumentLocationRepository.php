<?php


namespace App\Repositories;


use App\Models\DocumentLocation;
use Illuminate\Database\Eloquent\Collection;

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

    public function getAllWithDocuments(bool $withLocation = true): Collection
    {
        return $this->model->withDocuments($withLocation)->get();
    }
}
