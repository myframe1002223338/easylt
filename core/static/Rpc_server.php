<?php
class Rpc_server{
    public $serv;
    private function __clone(){//禁用克隆模式
        // TODO: Implement __clone() method.
    }
    public function __construct($ip=null,$port=null){
        if(RPC_SERV_ON_OFF!=1){
            exit('请在配置文件config_swoole.php中开启RPC服务器开关');
        }
        if(php_sapi_name()!=='cli'){
            exit('该服务只能运行在cli模式下');
        }
        if(!extension_loaded('swoole')){
            exit('请安装swoole扩展');
        }
        if($ip==null && $port!=null){
           $this->serv = new swoole_server(RPC_SERV_IP,$port,RPC_SERV_MODEL,SWOOLE_SOCK_TCP);
        }elseif($ip!=null && $port!=null){
            $this->serv = new swoole_server($ip,$port,RPC_SERV_MODEL,SWOOLE_SOCK_TCP);
        }else{
            $this->serv = new swoole_server(RPC_SERV_IP,RPC_SERV_PORT,RPC_SERV_MODEL,SWOOLE_SOCK_TCP);
        }
        if(RPC_SERV_TASK===1){
             $this->serv->set(['daemonize'=>RPC_SERV_DAEMONIZE,'worker_num'=>RPC_SERV_WORKER_NUM,'max_request'
             =>RPC_SERV_MAX_REQUEST,'task_worker_num'=>RPC_SERV_TASK_NUM]);
        }else{
             $this->serv->set(['daemonize'=>RPC_SERV_DAEMONIZE,'worker_num'=>RPC_SERV_WORKER_NUM,'max_request'
             =>RPC_SERV_MAX_REQUEST]);
        }
    }
    public function connect($func){
        $this->serv->on('connect',function($serv,$fd)use($func){
            $func();
        });
    }
    public function receive($func){
        $this->serv->on('receive',function($serv,$fd,$from_id,$data)use($func){
               $arr = explode('(',$data);
               $function_name = $arr[0];
               $function_param = explode(',',rtrim($arr[1],')'));
               $result = $func($function_name,$function_param);
               if(is_array($result)){//如果发送的数据为数组则自动转换为json格式,否则会发送失败;
                   $result = json_encode($result,256+64);
               }
               $serv->send($fd,$result);
               if(RPC_SERV_TASK===1){
                   $serv->task($function_param);
               }
        });
    }
    public function task($func){
        $this->serv->on('task',function($serv,$task_id,$from_id,$data)use($func){
            $func($data);
            $this->serv->finish(true);
        });
    }
    public function finish($func){
        $this->serv->on('finish',function($serv,$task_id,$data)use($func){
            $func($data);
        });
    }
    public function close($func){
        $this->serv->on('close',function($serv,$fd)use($func){
            $func();
        });
    }
    public function start(){
        $this->serv->start();
    }
    public function __destruct(){
        unset($this->serv);
    }
}