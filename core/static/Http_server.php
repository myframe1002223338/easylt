<?php
class Http_server{
    public $serv;
    private function __clone(){//禁用克隆模式
        // TODO: Implement __clone() method.
    }
    public function __construct($ip=null,$port=null){
        if(HTTP_SERV_ON_OFF!=1){
            exit('请在配置文件config_swoole.php中开启HTTP服务器开关');
        }
        if(php_sapi_name()!=='cli'){
            exit('该服务只能运行在cli模式下');
        }
        if(!extension_loaded('swoole')){
            exit('请安装swoole扩展');
        }
        if($ip==null && $port!=null){
            $this->serv = new swoole_http_server(HTTP_SERV_IP,$port);
        }elseif($ip!=null && $port!=null){
            $this->serv = new swoole_http_server($ip,$port);
        }else{
            $this->serv = new swoole_http_server(HTTP_SERV_IP,HTTP_SERV_PORT);
        }
        if(HTTP_SERV_TASK===1){
            $this->serv->set(['daemonize'=>HTTP_SERV_DAEMONIZE,'worker_num'=>HTTP_SERV_WORKER_NUM,'max_request'
            =>HTTP_SERV_MAX_REQUEST,'task_worker_num'=>HTTP_SERV_TASK_NUM]);
        }else{
            $this->serv->set(['daemonize'=>HTTP_SERV_DAEMONIZE,'worker_num'=>HTTP_SERV_WORKER_NUM,'max_request'
            =>HTTP_SERV_MAX_REQUEST]);
        }
    }
    public function receive($func){
        $GLOBALS['server'] = $this->serv;
        $this->serv->on('request',function($request,$response)use($func){
            $post = [];
            foreach($request->post as $v){//对传入的post数据进行处理,否则无法成功发送数据;
                $post[] = json_decode($v,true);
            }
            $post = json_encode($post,256+64);
            $get = json_encode($request->get,256+64);//对传入的get数据进行处理,否则无法成功发送数据;
            $data = ['post'=>$post,'get'=>$get];
            //获取头信息
            $headers_message = [];
            foreach(getallheaders() as $key => $value){
                $headers_message[$key] = $value;
            }
            $send = $func($post,$get,$headers_message);
            if(is_array($send)){//如果发送的数据为数组则自动转换为json格式,否则会发送失败;
                $send = json_encode($send,256+64);
            }
            $response->header('Content-Type:test','html;charset=utf-8');
            if(HTTP_SERV_TASK===1){
                $GLOBALS['server']->task($data);
            }
            $response->end($send);
        });
    }
    public function task($func){
        $this->serv->on('task',function($serv,$task_id,$from_id,$data)use($func){
            $func($data['post'],$data['get']);
            $this->serv->finish(true);
        });
    }
    public function finish($func){
        $this->serv->on('finish',function($serv,$task_id,$data)use($func){
            $func($data);
        });
    }
    public function start(){
        $this->serv->start();
    }
    public function __destruct(){
        unset($this->serv);
        unset($GLOBALS['server']);
    }
}