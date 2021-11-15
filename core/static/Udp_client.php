<?php
class Udp_client{
    public $cli;
    private function __clone(){
        // TODO: Implement __clone() method.
    }
    public function __construct($ip=null,$port=null){
        if(!extension_loaded('swoole')){
            exit('请安装swoole扩展');
        }
        $this->cli = new swoole_client(SWOOLE_SOCK_UDP);
        if($ip==null && $port!=null){
            $this->cli->connect(UDP_CLI_IP,$port) or die('连接失败，请检查配置及UDP服务器是否启动！');
        }elseif($ip!=null && $port!=null){
            $this->cli->connect($ip,$port) or die('连接失败，请检查配置及UDP服务器是否启动！');
        }else{
            $this->cli->connect(UDP_CLI_IP,UDP_CLI_PORT) or die('连接失败，请检查配置及UDP服务器是否启动！');
        }
        
    }
    public function send($func){
        $data = $func();
        if($data===null){
            $data = md5(time().mt_rand(1,999));
        }
        if(is_array($data)){
            $data = json_encode($data,256+64);
        }
        $this->cli->send($data) or die('发送失败');
    }
    public function receive($func){
        $data = $this->cli->recv();
        if(is_array(json_decode($data,true))){
            $data = json_decode($data,true);
        }
        $func($data);
        $data or die('接收失败');
    }
    public function __destruct(){
        unset($this->cli);
    }
}