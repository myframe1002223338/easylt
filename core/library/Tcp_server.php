<?php
class Tcp_server{
    public $serv;
    private function __clone(){//禁用克隆模式
        // TODO: Implement __clone() method.
    }
    public function __construct($ip=null,$port=null){
        if(TCP_SERV_ON_OFF!=1){
            exit('请在配置文件config_swoole.php中开启TCP服务器开关');
        }
        if(php_sapi_name()!=='cli'){
            exit('该服务只能运行在cli模式下');
        }
        if(!extension_loaded('swoole')){
            exit('请安装swoole扩展');
        }
        if($ip==null && $port!=null){
            $this->serv = new swoole_server(TCP_SERV_IP,$port,TCP_SERV_MODEL,SWOOLE_SOCK_TCP);
        }elseif($ip!=null && $port!=null){
            $this->serv = new swoole_server($ip,$port,TCP_SERV_MODEL,SWOOLE_SOCK_TCP);
        }else{
            $this->serv = new swoole_server(TCP_SERV_IP,TCP_SERV_PORT,TCP_SERV_MODEL,SWOOLE_SOCK_TCP);
        }
        if(TCP_SERV_TASK===1){
             $this->serv->set(['daemonize'=>TCP_SERV_DAEMONIZE,'worker_num'=>TCP_SERV_WORKER_NUM,'max_request'
             =>TCP_SERV_MAX_REQUEST,'task_worker_num'=>TCP_SERV_TASK_NUM]);
        }else{
             $this->serv->set(['daemonize'=>TCP_SERV_DAEMONIZE,'worker_num'=>TCP_SERV_WORKER_NUM,'max_request'
             =>TCP_SERV_MAX_REQUEST]);
        }
    }
    public function connect($func){
        $this->serv->on('connect',function($serv,$fd)use($func){
            $func();
        });
    }
    public function receive($func){
        $this->serv->on('receive',function($serv,$fd,$from_id,$data)use($func){
            $send = $func($data);
            if(is_array($send)){//如果发送的数据为数组则自动转换为json格式,否则会发送失败;
                $send = json_encode($send,256+64);
            }
            $serv->send($fd,$send);
            if(TCP_SERV_TASK===1){
                $serv->task($data);
            }
        });
    }
    public function task($func){
        $this->serv->on('task',function($serv,$task_id,$reactor_id,$data)use($func){
            $func($data,$task_id,$reactor_id);
            $this->serv->finish(['reactor_id'=>$reactor_id,'state'=>true]);
        });
    }
    public function finish($func){
        $this->serv->on('finish',function($serv,$task_id,$data)use($func){
            $state = $data['state'];
            $reactor_id = $data['reactor_id'];
            $func($state,$task_id,$reactor_id);
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