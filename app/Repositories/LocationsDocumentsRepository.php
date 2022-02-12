<?php


namespace App\Repositories;


use App\LocationsDocuments;

/**
 * Class LocationsDocumentsRepository
 * @package App\Repositories
 */
class LocationsDocumentsRepository
{
    private LocationsDocuments $model;

    public function __construct(LocationsDocuments $locationsDocuments)
    {
        $this->model = $locationsDocuments;
    }

    public function getLocationsDocuments(int $id = null)
    {
        if ($id) {
            return $this->model::findOrFail($id);
        }
        return $this->model::all();
    }
}
