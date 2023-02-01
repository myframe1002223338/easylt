<?php
//加载配置文件
include('..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'core'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'config.php');
error_reporting(ERROR_STATE);
header('Content-type:text/html;charset=utf-8');
date_default_timezone_set(TIMEZONE);
include(CORE_PATH.'config'.D.'config_controller.php');
@include(CORE_PATH.'config'.D.'config_swoole.php');
include(CORE_PATH.'config'.D.'config_rabbitmq.php');
include(APP_PATH.'config.php');
include(CORE_PATH.'lib'.D.'helper.php');

//动态加载类库
function autoload(){
    include(ROOT_PATH.'core'.D.'static'.D.'Inter.php');
    include(ROOT_PATH.'core'.D.'static'.D.'Connect_mysql.php');
    include(ROOT_PATH.'core'.D.'static'.D.'Connect_redis.php');
    include(ROOT_PATH.'core'.D.'static'.D.'Curl.php');
    include(ROOT_PATH.'core'.D.'static'.D.'Curl_get.php');
    include(ROOT_PATH.'core'.D.'static'.D.'Orm.php');
    include(ROOT_PATH.'core'.D.'static'.D.'Tcp_client.php');
    include(ROOT_PATH.'core'.D.'static'.D.'Udp_client.php');
    include(ROOT_PATH.'core'.D.'static'.D.'Http_client.php');
    include(ROOT_PATH.'core'.D.'static'.D.'Rpc_client.php');
    include(ROOT_PATH.'extend'.D.'rabbitmq'.D.'vendor'.D.'autoload.php');
    include(ROOT_PATH.'extend'.D.'phpexcel'.D.'PHPExcel.php');
    include(ROOT_PATH.'core'.D.'static'.D.'Simple_producter.php');
    include(ROOT_PATH.'core'.D.'static'.D.'Simple_consumer.php');
    include(ROOT_PATH.'core'.D.'static'.D.'Fanout_producter.php');
    include(ROOT_PATH.'core'.D.'static'.D.'Routing_producter.php');
    include(ROOT_PATH.'core'.D.'static'.D.'Topic_producter.php');
    include(ROOT_PATH.'core'.D.'static'.D.'Dead_producter.php');
}
spl_autoload_register('autoload');

use core\static_formwork\Inter\Inter as Inter;
//实例化API数据返回输出类库
$ob_inter = new Inter;

use core\static_formwork\Connect_mysql\Connect_mysql as Connect_mysql;
//加载mysql连接类库
$mysql_conn = Connect_mysql::get();

//实例化mysql-ORM类库
$mysql_orm = new Orm;

use core\static_formwork\Connect_redis\Connect_redis as Connect_redis;
//是否使用redis,默认不使用,请在config.php中配置是否使用;
if(REDIS_INCLUDE==1){
    $redis = Connect_redis::get();
}

use core\static_formwork\Curl\Curl as Curl;
//实例化curl-post数据传输类库
$curl_post = new Curl;

use core\static_formwork\Curl_get\Curl_get as Curl_get;
//实例化curl-get数据传输类库
$curl_get = new Curl_get;

//实例化PHPExcel类库
$excel = new PHPExcel();

if(URL_ON_OFF==1){
    //设置API跨域参数(单域名或所有域名(*)配置)
    header('Access-Control-Allow-Origin:'.SINGLE_URL);
    header('Access-Control-Allow-Methods:*');
    header('Access-Control-Request-Methods:*');
    //响应的设置
    header('Access-Control-Allow-Headers:*');
    //请求的设置
    header('Access-Control-Request-Headers:*');
    //响应请求是否可以暴露于该页面,true为允许,false为不允许
    header('Access-Control-Allow-Credentials: true');
    //发送一个报头，告诉浏览器当前页面不进行缓存,每次访问的时间必须从服务器上读取最新的数据,会影响性能,默认不应用
    //header("Cache-Control: no-cache, must-revalidate");
}else{
    //设置API跨域参数(多域名配置)
    foreach(ALL_URL as $v){
        header('Access-Control-Allow-Origin:'.$v);
        header('Access-Control-Allow-Methods:*');
        header('Access-Control-Request-Methods:*');
        header('Access-Control-Allow-Headers:*');
        header('Access-Control-Request-Headers:*');
        header('Access-Control-Allow-Credentials: true');
        //header("Cache-Control: no-cache, must-revalidate");
    }
}

//获取头信息
$headers_message = [];
foreach(getallheaders() as $key => $value){
    $headers_message[$key] = $value;
}

//验证头信息,默认不开启,请在config_controller.php中配置是否开启;
if(AUTH_ON_OFF==1){
    //头信息Authorization验证,不合法的请求将拒绝;
    if($headers_message['Authorization']!==AUTH){
        $ob_inter->state(401,'Authorization is wrong!','头信息验证失败！');
        exit;
    }
}

//调用route方法获取route请求参数用于路由分发
include(CORE_PATH.'route.php');
core\route_query();

//解析get请求
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
            $this->json_value = ['init'=>null];//如果API请求为get请求,初始化空数组;
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

//预加载model模型、logic逻辑文件,提取$response数据;
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

//加载API数据返回运行类库并调用
include(CORE_PATH.'base_api.php');
class Response{
    public static function data($response){
        global $ob_inter;
        BaseApi::api_run($ob_inter,$response);
    }
}
function response(){
    global $response;
    Response::data($response);
}

//根据路由运行控制器
if(file_exists($query_controller.'.php') && $query_controller!='index'){
    include($query_controller.'.php');
}elseif($query_controller=='index'){
    new Index;
}else{
    exit('API路由配置有误!');
}

