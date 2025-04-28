<?php
namespace App\Commons\Cache;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

Trait HandleCache
{
    public static function rememberCache($cacheKey, $callback, $ttl) {
        return Cache::remember($cacheKey, $ttl, function () use ($callback) {
            return $callback();
        });
    }
    public static function addDataCache($cacheKey, $data, $ttl = 60)
    {
        $cachedData = Cache::get($cacheKey, []);
        $cachedData[] = new $data;

        Cache::remember($cacheKey, $cachedData, $ttl);
    }
    
}