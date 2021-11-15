<?php
class Timer{
    public static $time_routing;
    public static function loop($func,$ttl,$end=null){
        if(php_sapi_name()!=='cli'){
            exit('该服务只能运行在cli模式下');
        }
        if(!extension_loaded('swoole')){
            exit('请安装swoole扩展');
        }
        swoole_timer_tick($ttl,function($time_id)use($func,$ttl,$end){
            Timer::$time_routing = $time_id;
            $func();
            if($end!=null){
                $end_time = $end - $ttl;
                swoole_timer_after($end_time,function()use($time_id){
                    swoole_timer_clear($time_id);
                });
            }
        });
    }
    public static function single($func,$ttl){
        if(php_sapi_name()!=='cli'){
            exit('该服务只能运行在cli模式下');
        }
        if(!extension_loaded('swoole')){
            exit('请安装swoole扩展');
        }
        swoole_timer_after($ttl,function()use($func){
            $func();
        });
    }
    public static function clear(){
        if(php_sapi_name()!=='cli'){
            exit('该服务只能运行在cli模式下');
        }
        if(!extension_loaded('swoole')){
            exit('请安装swoole扩展');
        }
        swoole_timer_clear(Timer::$time_routing);
    }
}