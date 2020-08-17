<?php


namespace App\Http\services;


use App\Repositories\AdminInformationRepository;
use App\Repositories\AdminRolesRepository;
use App\Repositories\CacheRepository;

class AdminGrantsService
{
    private $adminInformationRepository;
    private $adminRolesRepository;
    private $cacheRepository;

    public function __construct(
        AdminRolesRepository $adminRolesRepository,
        AdminInformationRepository $adminInformationRepository,
        CacheRepository $cacheRepository
    )
    {
        $this->adminRolesRepository = $adminRolesRepository;
        $this->adminInformationRepository = $adminInformationRepository;
        $this->cacheRepository = $cacheRepository;
    }

    public function getAdminsGrants(int $idUser) {
        $result = $this->cacheRepository->cacheAdminGrants($idUser, 'get');
        if (isset($result)) {
            return $result;
        } else {
            $result = [];
            try {
                $adminInfo = $this->adminInformationRepository->getAdmins($idUser)->toArray();
                $adminRoles = $this->adminRolesRepository->getRolesActions();

                foreach ($adminInfo as $id_point_of_sale => $arrayRoles) {
                    $result[$id_point_of_sale] = [];
                    foreach ($arrayRoles as $role) {
                        $result[$id_point_of_sale] = array_merge($adminRoles[$role['id_ar']], $result[$id_point_of_sale]);
                    }
                    $result[$id_point_of_sale] = array_unique($result[$id_point_of_sale]);
                }
                $this->cacheRepository->cacheAdminGrants($idUser, 'create', $result);
                return $result;
            } catch (\Exception $e) {
                return null;
            }
        }
    }
}
