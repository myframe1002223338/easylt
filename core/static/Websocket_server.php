<?php
class Websocket_server{
    public $serv;
    public $data;
    public $fd;
    public $mysql;
    public $redis;
    private function __clone(){//禁用克隆模式
        // TODO: Implement __clone() method.
    }
    public function create(){
        if(WEBSOCKET_SERV_ON_OFF!=1){
             exit('请在配置文件config_swoole.php中开启WEBSOCKET服务器开关');
        }
        if(php_sapi_name()!=='cli'){
            exit('该服务只能运行在cli模式下');
        }
        if(!extension_loaded('swoole')){
            exit('请安装swoole扩展');
        }
        $this->serv = new swoole_websocket_server(WEBSOCKET_SERV_IP,WEBSOCKET_SERV_PORT);
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
    public function receive($func){
        $this->serv->on('message',function($serv,$request)use($func){
            $this->data = $request->data;
            $this->fd = $request->fd;
            if(WEBSOCKET_CHAT_MODEL===1){
                swoole_timer_tick(WEBSOCKET_RESPONSE_TIME,function($timer_id,$serv)use($func){
                       $send = $func($this->data);
                       if(is_array($send)){//如果发送的数据为数组则自动转换为json格式,否则会发送失败;
                       $send = json_encode($send,256+64);
                       }
                       if($send!=null){
                           $serv->push($this->fd,$send);
                       }
                },$serv);
            }else{
                swoole_timer_tick(WEBSOCKET_RESPONSE_TIME,function($timer_id,$serv)use($func){
                      $send = $func($this->data);
                      if(is_array($send)){//如果发送的数据为数组则自动转换为json格式,否则会发送失败;
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
    public function workerstart($func){
        $this->serv->on('WorkerStart',function($serv,$worker_id)use($func){
            $mysql_conn = mysqli_connect(DB_HOST,DB_USER,DB_PWD);//worker进程直接引入外部mysql连接会出现问题,在这里实现连接;
            mysqli_set_charset($mysql_conn,DB_CHARSET);
            mysqli_select_db($mysql_conn,DB_NAME);
            $this->mysql = $mysql_conn;
            $redis_conn = new \Redis();//worker进程直接引入外部redis连接会出现问题,在这里实现连接;
            $redis_conn->connect(REDIS_HOST,REDIS_PORT);
            $redis_conn->auth(REDIS_AUTH);
            $redis_conn->select(REDIS_DBNAME);
            $this->redis = $redis_conn;
            swoole_timer_tick(WEBSOCKET_RESPONSE_TIME,function($timer_id,$serv)use($func){
                $send = $func($this->data,$this->mysql,$this->redis);
                if(is_array($send)){//如果发送的数据为数组则自动转换为json格式,否则会发送失败;
                    $send = json_encode($send,256+64);
                }
                if($send!=null){
                    $serv->push($this->fd,$send);
                }
            },$serv);
        });
    }
    public function task($func){
        $this->serv->on('task',function($serv,$task_id,$from_id,$data)use($func){
            $func($data);
            $serv->finish($data);
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
        unset($this->mysql);
        unset($this->redis);
    }
}