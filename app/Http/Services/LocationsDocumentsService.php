<?php
/**
 * Created by PhpStorm.
 * User: barenad
 * Date: 20.08.21
 * Time: 16:00
 */

namespace App\Http\Services;


use App\Models\User;
use App\Policies\LocationsDocumentsPolicy;
use App\Repositories\LocationsDocumentsRepository;
use App\Repositories\PointOfSaleRepository;

/**
 * Class LocationsDocumentsService
 * @package App\Http\Services
 */
class LocationsDocumentsService
{
    private $locationsDocumentsRepository;

    /**
     * LocationsDocumentsService constructor.
     * @param LocationsDocumentsRepository $locationsDocumentsRepository
     */
    public function __construct(LocationsDocumentsRepository $locationsDocumentsRepository)
    {
        $this->locationsDocumentsRepository = $locationsDocumentsRepository;
    }

    /**
     * @param int|null $id
     * @return \App\LocationsDocuments[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getLocationsDocuments(int $id = null)
    {
        return $this->locationsDocumentsRepository->getLocationsDocuments($id);
    }

    /**
     * @param User $user
     * @param int $id
     * @param int|null $id_document
     * @return \App\LocationsDocuments[]|\Illuminate\Database\Eloquent\Collection
     */
    public function changeLocationsDocuments(
        User $user,
        int $id,
        int $id_document = null
    ) {
        $locationDocument = $this->locationsDocumentsRepository->getLocationsDocuments($id);
        if (LocationsDocumentsPolicy::canUpdate($user)) {
            $locationDocument->fill([
                'id_d' => $id_document,
            ])->save();
            CacheService::cacheProductsInfo('delete', 'locations_documents');
        } else {
            GeneratedAborting::accessDeniedGrandsAdmin();
        }
        return $locationDocument;
    }
}
