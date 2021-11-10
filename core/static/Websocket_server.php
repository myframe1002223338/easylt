<?php
class Websocket_server{
    public $serv;
    public $data;
    public $fd;
    private function __clone(){
        // TODO: Implement __clone() method.
    }
    public function __construct($ip=null,$port=null){
        if(WEBSOCKET_SERV_ON_OFF!=1){
            exit('请在配置文件config_swoole.php中开启WEBSOCKET服务器开关');
        }
        if(php_sapi_name()!=='cli'){
            exit('该服务只能运行在cli模式下');
        }
        if(!extension_loaded('src')){
            exit('请安装swoole扩展');
        }
        if($ip==null && $port!=null){
            $this->serv = new swoole_websocket_server(WEBSOCKET_SERV_IP,$port);
        }elseif($ip!=null && $port!=null){
            $this->serv = new swoole_websocket_server($ip,$port);
        }else{
            $this->serv = new swoole_websocket_server(WEBSOCKET_SERV_IP,WEBSOCKET_SERV_PORT);
        }
        if(WEBSOCKET_SERV_TASK===1){
             $this->serv->set(['daemonize'=>WEBSOCKET_SERV_DAEMONIZE,'worker_num'=>WEBSOCKET_SERV_WORKER_NUM
             ,'max_request'=>WEBSOCKET_SERV_MAX_REQUEST,'task_worker_num'=>WEBSOCKET_SERV_TASK_NUM,'heartbeat_check_interval'=>WEBSOCKET_HEARTHBEAT_CHECK_INTERVAL,'heartbeat_idle_time'=>WEBSOCKET_HEARTHBEAT_IDLE_TIME]);
        }else{
             $this->serv->set(['daemonize'=>WEBSOCKET_SERV_DAEMONIZE,'worker_num'=>WEBSOCKET_SERV_WORKER_NUM
             ,'max_request'=>WEBSOCKET_SERV_MAX_REQUEST,'heartbeat_check_interval'
             =>WEBSOCKET_HEARTHBEAT_CHECK_INTERVAL,'heartbeat_idle_time'=>WEBSOCKET_HEARTHBEAT_IDLE_TIME]);
        }
    }
    public function connect($func){
        $this->serv->on('open',function($serv,$request)use($func){
        $func();
        });
    }
    public function receive($func_read,$func_write='empty_null'){
        $this->serv->on('message',function($serv,$request)use($func_read,$func_write){
            $this->data = $request->data;
            $this->fd = $request->fd;
            if($func_write!='empty_null'){
                $func_write($this->data);
            }
            if(WEBSOCKET_CHAT_MODEL===1){
                swoole_timer_tick(WEBSOCKET_RESPONSE_TIME,function($timer_id,$serv)use($func_read){
                       $send = $func_read($this->data);
                       if(is_array($send)){
                       $send = json_encode($send,256+64);
                       }
                       if($send!=null){
                           $serv->push($this->fd,$send);
                       }
                },$serv);
            }else{
                swoole_timer_tick(WEBSOCKET_RESPONSE_TIME,function($timer_id,$serv)use($func_read){
                      $send = $func_read($this->data);
                      if(is_array($send)){
                      $send = json_encode($send,256+64);
                      }
                      if($send!=null){
                          foreach($serv->connections as $fd){
                              $serv->push($fd,$send);
                          }
                      }
                },$serv);
            }
            if(WEBSOCKET_SERV_TASK===1){
                $serv->task($this->data);
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
        $this->serv->on('close',function($serv,$request)use($func){
            $func();
        });
    }
    public function start(){
        $this->serv->start();
    }
    public function __destruct(){
        unset($this->serv);
        unset($this->data);
        unset($this->fd);
    }
}