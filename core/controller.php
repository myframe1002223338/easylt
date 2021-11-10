<?php
include('..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'core'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'config.php');
error_reporting(ERROR_STATE);
header('Content-type:text/html;charset=utf-8');
date_default_timezone_set('Asia/chongqing');
include('..'.D.'..'.D.'..'.D.'core'.D.'config'.D.'config_controller.php');
@include('..'.D.'..'.D.'..'.D.'core'.D.'config'.D.'config_swoole.php');
include('..'.D.'..'.D.'..'.D.'core'.D.'config'.D.'config_rabbitmq.php');

function autoload(){
    include('..'.D.'..'.D.'..'.D.'core'.D.'static'.D.'Inter.php');
    include('..'.D.'..'.D.'..'.D.'core'.D.'static'.D.'Connect_mysql.php');
    include('..'.D.'..'.D.'..'.D.'core'.D.'static'.D.'Connect_redis.php');
    include('..'.D.'..'.D.'..'.D.'core'.D.'static'.D.'Curl.php');
    include('..'.D.'..'.D.'..'.D.'core'.D.'static'.D.'Curl_get.php');
    include('..'.D.'..'.D.'..'.D.'core'.D.'static'.D.'Orm.php');
    include('..'.D.'..'.D.'..'.D.'core'.D.'static'.D.'Tcp_client.php');
    include('..'.D.'..'.D.'..'.D.'core'.D.'static'.D.'Udp_client.php');
    include('..'.D.'..'.D.'..'.D.'core'.D.'static'.D.'Http_client.php');
    include('..'.D.'..'.D.'..'.D.'core'.D.'static'.D.'Rpc_client.php');
    include('..'.D.'..'.D.'..'.D.'extend'.D.'rabbitmq'.D.'vendor'.D.'autoload.php');
    include('..'.D.'..'.D.'..'.D.'core'.D.'static'.D.'Simple_producter.php');
    include('..'.D.'..'.D.'..'.D.'core'.D.'static'.D.'Simple_consumer.php');
    include('..'.D.'..'.D.'..'.D.'core'.D.'static'.D.'Fanout_producter.php');
    include('..'.D.'..'.D.'..'.D.'core'.D.'static'.D.'Routing_producter.php');
    include('..'.D.'..'.D.'..'.D.'core'.D.'static'.D.'Topic_producter.php');
    include('..'.D.'..'.D.'..'.D.'core'.D.'static'.D.'Dead_producter.php');
}
spl_autoload_register('autoload');

use core\static_formwork\Inter\Inter as Inter;
$ob_inter = new Inter;

use core\static_formwork\Connect_mysql\Connect_mysql as Connect_mysql;
$mysql_conn = Connect_mysql::get();

$mysql_orm = new Orm;

use core\static_formwork\Connect_redis\Connect_redis as Connect_redis;
if(REDIS_INCLUDE==1){
    $redis = Connect_redis::get();
}

use core\static_formwork\Curl\Curl as Curl;
$curl_post = new Curl;

use core\static_formwork\Curl_get\Curl_get as Curl_get;
$curl_get = new Curl_get;

if(URL_ON_OFF==1){
    header('Access-Control-Allow-Origin:'.SINGLE_URL);
}else{
    foreach(ALL_URL as $v){
        header('Access-Control-Allow-Origin:'.$v);
    }
}

if(AUTH_ON_OFF==1){
    $Authorization = [];
    foreach(getallheaders() as $key => $value){
        $Authorization[$key] = $value;
    }
    if($Authorization['Authorization']!==AUTH){
        $ob_inter->state('401','Authorization is wrong!','头信息验证失败！');
        die;
    }
}

include('..'.D.'..'.D.'..'.D.'core'.D.'route.php');
core\route_query();

if($query_get){
    parse_str($query_get,$query_get);
}else{
    parse_str($query_param,$query_get);
}

class Controller{
    public $json_value;
    public function catch_params(){
        $json = file_get_contents('php://input');
        if($json){
            $this->json_value = json_decode($json,true);
        }else{
            $this->json_value = ['init'=>null];
        }
        return json_encode($this->json_value);
    }
    public function __destruct(){
        unset($this->json_value);
    }
}
$ob_controller = new Controller;
$request = $ob_controller->catch_params();
$request = json_decode($request,true);

if(file_exists('..'.D.'..'.D.'model'.D.$query_model.'.php')){
    include('..'.D.'..'.D.'model'.D.$query_model.'.php');
}
if(file_exists('..'.D.'..'.D.APPLICATION_RENAME[0].'_model'.D.$query_model.'.php')){
    include('..'.D.'..'.D.APPLICATION_RENAME[0].'_model'.D.$query_model.'.php');
}
if(file_exists('..'.D.'logic'.D.$query_model.'.logic.php')){
    include('..'.D.'logic'.D.$query_model.'.logic.php');
}
if(file_exists('..'.D.APPLICATION_RENAME[4].'_logic'.D.$query_model.'.logic.php')){
    include('..'.D.APPLICATION_RENAME[4].'_logic'.D.$query_model.'.logic.php');
}

include('..'.D.'..'.D.'..'.D.'core'.D.'base.php');
class Response{
    public static function data($response){
        global $ob_inter;
        AutoLoad::api_run($ob_inter,$response);
    }
}

if(file_exists($query_controller.'.php') && $query_controller!='index'){
    include($query_controller.'.php');
}elseif($query_controller=='index'){
    new Index;
}else{
    exit('API路由配置有误!');
}

function onerror($message,$path=null){
    if($path===null){
        $error = date('y-m-d h:i:s',time()).$message.PHP_EOL;
        error_log($error,3,'..'.D.'..'.D.'..'.D.'core'.D.'log'.D.'errors.log');
    }else{
        $error = date('y-m-d h:i:s',time()).$message.PHP_EOL;
        error_log($error,3,$path);
    }
}