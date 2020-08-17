<?php


namespace App\Repositories;


use Illuminate\Support\Facades\Cache;

class CacheRepository
{
    public function cacheAdminGrants(int $idUser, string $action, $value = null) {
        $cacheTimeout = 30;
        $tags = ['adminGrants'];
        if (!isset($idUser)) {
            $cacheKey = 'cache_admin_grants_user_' . $idUser;
            switch ($action) {
                case 'get':
                    if (Cache::tags($tags)->has($cacheKey)) {
                        return Cache::tags($tags)->get($cacheKey);
                    }
                break;
                case 'create':
                    if (isset($value)) {
                        return Cache::tags($tags)->add($cacheKey, $value, $cacheTimeout);
                    }
                break;
                case 'delete':
                    if (Cache::tags($tags)->has($cacheKey)) {
                        return Cache::tags($tags)->forget($cacheKey);
                    }
                break;
            }
        } elseif ($action === 'flush') {
            Cache::tags($tags)->flush();
        }
        return null;
    }
}
