<?php
//同步redis,内存常驻运行模式下不可采用单例模式;
class Sync_redis{
    public static function co($func){
        if(REDIS_INCLUDE==1){
            $async_redis = new \Redis();
            $async_redis->connect(REDIS_HOST,REDIS_PORT);
            $async_redis->auth(REDIS_AUTH);
            $async_redis->select(REDIS_DBNAME);
        }
        $func($async_redis);
    }
}