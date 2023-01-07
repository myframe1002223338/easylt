<?php
class Udp_server{
    public $serv;
    private function __clone(){//禁用克隆模式
        // TODO: Implement __clone() method.
    }
    public function __construct($ip=null,$port=null){
        if(UDP_SERV_ON_OFF!=1){
            exit('请在配置文件config_swoole.php中开启UDP服务器开关');
        }
        if(php_sapi_name()!=='cli'){
            exit('该服务只能运行在cli模式下');
        }
        if(!extension_loaded('swoole')){
            exit('请安装swoole扩展');
        }
        if($ip==null && $port!=null){
            $this->serv = new swoole_server(UDP_SERV_IP,$port,UDP_SERV_MODEL,SWOOLE_SOCK_UDP);
        }elseif($ip!=null && $port!=null){
            $this->serv = new swoole_server($ip,$port,UDP_SERV_MODEL,SWOOLE_SOCK_UDP);
        }else{
            $this->serv = new swoole_server(UDP_SERV_IP,UDP_SERV_PORT,UDP_SERV_MODEL,SWOOLE_SOCK_UDP);
        }
        if(UDP_SERV_TASK===1){
             $this->serv->set(['daemonize'=>UDP_SERV_DAEMONIZE,'worker_num'=>UDP_SERV_WORKER_NUM,'max_request'=>UDP_SERV_MAX_REQUEST,'task_worker_num'=>UDP_SERV_TASK_NUM]);
        }else{
             $this->serv->set(['daemonize'=>UDP_SERV_DAEMONIZE,'worker_num'=>UDP_SERV_WORKER_NUM,'max_request'=>UDP_SERV_MAX_REQUEST]);
        }
    }
    public function receive($func){
        $this->serv->on('Packet', function ($serv,$data,$clientInfo)use($func){
            $send = $func($data);
            if(is_array($send)){//如果发送的数据为数组则自动转换为json格式,否则会发送失败;
               $send = json_encode($send,256+64);
            }
            $serv->sendto($clientInfo['address'],$clientInfo['port'],$send);
            if(UDP_SERV_TASK===1){
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
    public function start(){
        $this->serv->start();
    }
    public function __destruct(){
        unset($this->serv);
    }
}
