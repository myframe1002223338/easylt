<?php
//异步redis
use Swoole\Coroutine;
use function Swoole\Coroutine\run;
class Async_redis{
    public function __construct($func){
        if(php_sapi_name()!=='cli'){
            exit('该服务只能运行在cli模式下');
        }
        if(!extension_loaded('swoole')){
            exit('请安装swoole扩展');
        }
        run(function()use($func){
            $func();
        });
    }
    public static function co($func,$sleep=null){
        if(php_sapi_name()!=='cli'){
            exit('该服务只能运行在cli模式下');
        }
        if(!extension_loaded('swoole')){
            exit('请安装swoole扩展');
        }
        Coroutine::create(function()use($func,$sleep){
            if($sleep!=null){
                sleep($sleep);
            }
            if(REDIS_INCLUDE==1){
                $async_redis = new \Redis();
                $async_redis->connect(REDIS_HOST,REDIS_PORT);
                $async_redis->auth(REDIS_AUTH);
                $async_redis->select(REDIS_DBNAME);
            }
            $func($async_redis);
        });
    }
}