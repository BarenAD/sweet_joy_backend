<?php


namespace App\Http\Services;


use Illuminate\Support\Facades\Cache;

class CacheService
{
    public static function cacheAdminGrants(int $idUser, string $action, $value = null)
    {
        $cacheTimeout = 1800;
        $tags = ['adminGrants'];
        if (isset($idUser)) {
            $cacheKey = 'cache_admin_grants_user_' . $idUser;
            return CacheService::_PrivateAlgorithmCache($action, $tags, $cacheKey, $cacheTimeout, $value);
        } elseif ($action === 'flush') {
            return CacheService::_PrivateAlgorithmCache($action, $tags, null, null, null);
        }
        return null;
    }

    public static function cacheProductsInfo(string $action, string $subKey = null, $value = null)
    {
        $tags = ['cache_products_info'];
        $cacheKey = 'cache_products_info_' . $subKey;
        $cacheTimeout = 21600;
        return CacheService::_PrivateAlgorithmCache($action, $tags, $cacheKey, $cacheTimeout, $value);
    }

    public static function _PrivateAlgorithmCache(
        string $action,
        array $tags,
        string $cacheKey,
        int $cacheTimeout,
        $value = null
    ) {
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
            case 'flush':
                return Cache::tags($tags)->flush();
            break;
        }
        return null;
    }
}
