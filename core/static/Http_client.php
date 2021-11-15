<?php
class Http_client{
    public $ip;
    public $port;
    private function __clone(){//禁用克隆模式
        // TODO: Implement __clone() method.
    }
    public function __construct($ip=null,$port=null){
        $this->ip = $ip;
        $this->port = $port;
    }
    public function post($data){
        if(!extension_loaded('swoole')){
            exit('请安装swoole扩展');
        }
        $data = [$data];//无论请求数据类型是否为数组,都进行预处理封装一层数组;
        foreach ($data as $k=>$v){//对传入的数据进行处理,否则无法成功发送数据;
            $new_data[$k] =$v; 
        }
        $arr = [];
        foreach($new_data as $v){
            array_push($arr,json_encode($v,256+64));
        }
        $cu = curl_init();
        if($this->ip==null && $this->port!=null){
            curl_setopt($cu,CURLOPT_URL,HTTP_POST_CLI_IP.':'.$this->port);
        }elseif($this->ip!=null && $this->port!=null){
            curl_setopt($cu,CURLOPT_URL,$this->ip.':'.$this->port);
        }else{
            curl_setopt($cu,CURLOPT_URL,HTTP_POST_CLI_IP.':'.HTTP_POST_CLI_PORT);
        }
        curl_setopt($cu,CURLOPT_RETURNTRANSFER,HTTP_POST_RETURNTRANSFER);
        curl_setopt($cu,CURLOPT_POST,1);
        curl_setopt($cu,CURLOPT_POSTFIELDS,$arr);
        curl_setopt($cu,CURLOPT_SSL_VERIFYPEER,HTTP_POST_SSL_VERIFYPEER);
        curl_setopt($cu,CURLOPT_SSL_VERIFYHOST,HTTP_POST_SSL_VERIFYHOST);
        if(HTTP_POST_SSL_VERIFYPEER===1){
            curl_setopt($cu,CURLOPT_CAINFO,HTTP_POST_CAINFO);
        }
        curl_setopt($cu,CURLOPT_HEADER,HTTP_POST_HEADER);
        curl_setopt($cu,CURLOPT_TIMEOUT,HTTP_POST_TIMEOUT);
        $output = curl_exec($cu);
        curl_close($cu);
        return $output;
    }
    public function get($data){
        if(!extension_loaded('swoole')){
            exit('请安装swoole扩展');
        }
        $cu = curl_init();
        if($this->ip==null && $this->port!=null){
            curl_setopt($cu,CURLOPT_URL,HTTP_GET_CLI_IP.':'.$this->port.$data);
        }elseif($this->ip!=null && $this->port!=null){
            curl_setopt($cu,CURLOPT_URL,$this->ip.':'.$this->port.$data);
        }else{
            curl_setopt($cu,CURLOPT_URL,HTTP_GET_CLI_IP.':'.HTTP_GET_CLI_PORT.$data);
        }
        curl_setopt($cu,CURLOPT_RETURNTRANSFER,HTTP_GET_RETURNTRANSFER);
        curl_setopt($cu,CURLOPT_SSL_VERIFYPEER,HTTP_GET_SSL_VERIFYPEER);
        curl_setopt($cu,CURLOPT_SSL_VERIFYHOST,HTTP_GET_SSL_VERIFYHOST);
        if(HTTP_GET_SSL_VERIFYPEER===1){
            curl_setopt($cu,CURLOPT_CAINFO,HTTP_GET_CAINFO);
        }
        curl_setopt($cu,CURLOPT_HEADER,HTTP_GET_HEADER);
        curl_setopt($cu,CURLOPT_TIMEOUT,HTTP_GET_TIMEOUT);
        $output = curl_exec($cu);
        curl_close($cu);
        return $output;
    }
    public function __destruct(){
        unset($this->ip);
        unset($this->port);
    }
}