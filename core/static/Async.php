<?php
use Swoole\Coroutine;
use function Swoole\Coroutine\run;
class Async{
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
    public static function co($func){
        if(php_sapi_name()!=='cli'){
            exit('该服务只能运行在cli模式下');
        }
        if(!extension_loaded('swoole')){
            exit('请安装swoole扩展');
        }
        Coroutine::create(function()use($func){
            $func();
        });
    }
}

