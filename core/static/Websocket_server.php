<?php
class Websocket_server{
    public $serv;
    public $data;
    public $fd;
    private function __clone(){//禁用克隆模式
        // TODO: Implement __clone() method.
    }
    public function __construct($ip=null,$port=null){
        if(WEBSOCKET_SERV_ON_OFF!=1){
            exit('请在配置文件config_swoole.php中开启WEBSOCKET服务器开关');
        }
        if(php_sapi_name()!=='cli'){
            exit('该服务只能运行在cli模式下');
        }
        if(!extension_loaded('swoole')){
            exit('请安装swoole扩展');
        }
        if(WEBSOCKET_SSL_ON_OFF===1){
            if($ip==null && $port!=null){
                $this->serv = new swoole_websocket_server(WEBSOCKET_SERV_IP,$port,SWOOLE_PROCESS,SWOOLE_SOCK_TCP | SWOOLE_SSL);
            }elseif($ip!=null && $port!=null){
                $this->serv = new swoole_websocket_server($ip,$port,SWOOLE_PROCESS,SWOOLE_SOCK_TCP | SWOOLE_SSL);
            }else{
                $this->serv = new swoole_websocket_server(WEBSOCKET_SERV_IP,WEBSOCKET_SERV_PORT,SWOOLE_PROCESS,SWOOLE_SOCK_TCP | SWOOLE_SSL);
            }
            if(WEBSOCKET_SERV_TASK===1){
                $this->serv->set(['daemonize'=>WEBSOCKET_SERV_DAEMONIZE,'worker_num'=>WEBSOCKET_SERV_WORKER_NUM,'max_request'=>WEBSOCKET_SERV_MAX_REQUEST,'task_worker_num'=>WEBSOCKET_SERV_TASK_NUM,'heartbeat_check_interval'=>WEBSOCKET_HEARTHBEAT_CHECK_INTERVAL,'heartbeat_idle_time'=>WEBSOCKET_HEARTHBEAT_IDLE_TIME,'ssl_cert_file'=>CERTIFICATE,'ssl_key_file'=>PRIVATEKEY]);
            }else{
                $this->serv->set(['daemonize'=>WEBSOCKET_SERV_DAEMONIZE,'worker_num'=>WEBSOCKET_SERV_WORKER_NUM,'max_request'=>WEBSOCKET_SERV_MAX_REQUEST,'heartbeat_check_interval' =>WEBSOCKET_HEARTHBEAT_CHECK_INTERVAL,'heartbeat_idle_time'=>WEBSOCKET_HEARTHBEAT_IDLE_TIME,'ssl_cert_file'=>CERTIFICATE,'ssl_key_file'=>PRIVATEKEY]);
            }
        }else{
            if($ip==null && $port!=null){
                $this->serv = new swoole_websocket_server(WEBSOCKET_SERV_IP,$port);
            }elseif($ip!=null && $port!=null){
                $this->serv = new swoole_websocket_server($ip,$port);
            }else{
                $this->serv = new swoole_websocket_server(WEBSOCKET_SERV_IP,WEBSOCKET_SERV_PORT);
            }
            if(WEBSOCKET_SERV_TASK===1){
                $this->serv->set(['daemonize'=>WEBSOCKET_SERV_DAEMONIZE,'worker_num'=>WEBSOCKET_SERV_WORKER_NUM,'max_request'=>WEBSOCKET_SERV_MAX_REQUEST,'task_worker_num'=>WEBSOCKET_SERV_TASK_NUM,'heartbeat_check_interval'=>WEBSOCKET_HEARTHBEAT_CHECK_INTERVAL,'heartbeat_idle_time'=>WEBSOCKET_HEARTHBEAT_IDLE_TIME]);
            }else{
                $this->serv->set(['daemonize'=>WEBSOCKET_SERV_DAEMONIZE,'worker_num'=>WEBSOCKET_SERV_WORKER_NUM,'max_request'=>WEBSOCKET_SERV_MAX_REQUEST,'heartbeat_check_interval' =>WEBSOCKET_HEARTHBEAT_CHECK_INTERVAL,'heartbeat_idle_time'=>WEBSOCKET_HEARTHBEAT_IDLE_TIME]);
            }
        }
    }
    public function connect($func){
        $this->serv->on('open',function($serv,$request)use($func){
            $this->data = $request->data;
            $this->fd = $request->fd;
            if(WEBSOCKET_RESPONSE_TIME_MODEL===1){
                swoole_timer_tick(WEBSOCKET_RESPONSE_TIME,function($timer_id)use($func,$serv){
                    $send = $func($this->data,$this->fd);
                    if(is_array($send)){//如果发送的数据为数组则自动转换为json格式,否则会发送失败;
                        $send = json_encode($send,256+64);
                    }
                    if($send!=null){
                        @$serv->push($this->fd,$send);
                    }
                });
            }else{
                $send = $func($this->data,$this->fd);
                if(is_array($send)){//如果发送的数据为数组则自动转换为json格式,否则会发送失败;
                    $send = json_encode($send,256+64);
                }
                if($send!=null){
                    @$serv->push($this->fd,$send);
                }
            }
        });
    }
    public function receive($func){
        $this->serv->on('message',function($serv,$request)use($func){
            $this->data = $request->data;
            $data_parse = json_decode($this->data,true);
            $user_fd = $data_parse['fd'];
            $this->fd = $request->fd;
            if(WEBSOCKET_CHAT_MODEL===1){
                $send = $func($this->data,$this->fd);
                if(is_array($send)){//如果发送的数据为数组则自动转换为json格式,否则会发送失败;
                    $send = json_encode($send,256+64);
                }
                if($send!=null){
                    //如果客户端传入对方的fd时则发送数据给对方,否则发送给自己;
                    if($user_fd){
                        @$serv->push($user_fd,$send);
                    }else{
                        @$serv->push($this->fd,$send);
                    }
                }
            }else{
                $send = $func($this->data,$this->fd);
                if(is_array($send)){//如果发送的数据为数组则自动转换为json格式,否则会发送失败;
                    $send = json_encode($send,256+64);
                }
                if($send!=null){
                    foreach($serv->connections as $fd){
                        @$serv->push($fd,$send);
                    }
                }
            }
            if(WEBSOCKET_SERV_TASK===1){
                $serv->task($this->data);
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
            $func($fd);
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