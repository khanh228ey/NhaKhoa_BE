<?php
namespace App\Commons\Cache;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

Trait HandleCache
{
    public static function rememberCache($cacheKey, $callback, $ttl = 60) {
        return Cache::remember($cacheKey, now()->addMinutes($ttl), function () use ($callback) {
            return $callback();
        });
    }
    public static function addDataCache($cacheKey, $data, $ttl = 60)
    {
        $cachedData = Cache::get($cacheKey, []);
        $cachedData[] = new $data;

        Cache::put($cacheKey, $cachedData, Carbon::now()->addMinutes($ttl));
    }
    
}