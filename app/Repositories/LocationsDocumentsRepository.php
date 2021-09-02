<?php


namespace App\Repositories;


use App\LocationsDocuments;

/**
 * Class LocationsDocumentsRepository
 * @package App\Repositories
 */
class LocationsDocumentsRepository
{
    private $model;

    /**
     * LocationsDocumentsRepository constructor.
     * @param LocationsDocuments $locationsDocuments
     */
    public function __construct(LocationsDocuments $locationsDocuments)
    {
        $this->model = $locationsDocuments;
    }

    /**
     * @param int|null $id
     * @return LocationsDocuments[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getLocationsDocuments(int $id = null)
    {
        if ($id) {
            return $this->model::findOrFail($id);
        }
        return $this->model::all();
    }
}
