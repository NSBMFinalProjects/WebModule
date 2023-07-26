<?php
namespace App\Connnections;

use Redis;

class RedisDB
{
    /**
     * Connect with the Redis database
     **/
    public static function connect(): Redis
    {
        $redis = new Redis();
        $redis->connect($_ENV['REDIS_HOST'], $_ENV['REDIS_PORT']);
        return $redis;
    }
}
