<?php
//加载配置文件
include('..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'core'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'config_route.php');
include('..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'core'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'config.php');
error_reporting(ERROR_STATE);
header('Content-type:text/html;charset=utf-8');
date_default_timezone_set(TIMEZONE);
include(CORE_PATH.'config'.D.'config_controller.php');
@include(CORE_PATH.'config'.D.'config_swoole.php');
include(CORE_PATH.'config'.D.'config_rabbitmq.php');
include(APP_PATH.'config.php');
include(CORE_PATH.'common'.D.'helper.php');
include(EXTEND_PATH.'whoops'.D.'vendor'.D.'autoload.php');
//实例化whoops
if(DEBUG===1){
    $whoops = new \Whoops\Run;
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
    $whoops->register();
}

//动态加载类库
function autoload_controller(){
    include_once(CORE_PATH.'library'.D.'Inter.php');
    include_once(CORE_PATH.'library'.D.'Connect_mysql.php');
    include_once(CORE_PATH.'library'.D.'Connect_redis.php');
    include_once(CORE_PATH.'library'.D.'Curl.php');
    include_once(CORE_PATH.'library'.D.'Curl_get.php');
    include_once(CORE_PATH.'library'.D.'Orm.php');
    include_once(CORE_PATH.'library'.D.'Tcp_client.php');
    include_once(CORE_PATH.'library'.D.'Udp_client.php');
    include_once(CORE_PATH.'library'.D.'Http_client.php');
    include_once(CORE_PATH.'library'.D.'Rpc_client.php');
    include_once(EXTEND_PATH.'rabbitmq'.D.'vendor'.D.'autoload.php');
    include_once(EXTEND_PATH.'phpexcel'.D.'PHPExcel.php');
    include_once(CORE_PATH.'library'.D.'Simple_producter.php');
    include_once(CORE_PATH.'library'.D.'Simple_consumer.php');
    include_once(CORE_PATH.'library'.D.'Fanout_producter.php');
    include_once(CORE_PATH.'library'.D.'Routing_producter.php');
    include_once(CORE_PATH.'library'.D.'Topic_producter.php');
    include_once(CORE_PATH.'library'.D.'Dead_producter.php');
}
spl_autoload_register('autoload_controller');

use core\library\Inter\Inter as Inter;
//实例化API数据返回输出类库
$ob_inter = new Inter;

use core\library\Connect_mysql\Connect_mysql as Connect_mysql;
//加载mysql连接类库
$mysql_conn = Connect_mysql::get();

use core\library\Orm\Orm as Orm;
//实例化mysql-ORM类库
$mysql_orm = new Orm;

use core\library\Connect_redis\Connect_redis as Connect_redis;
//是否使用redis,默认不使用,请在config.php中配置是否使用;
if(REDIS_INCLUDE==1){
    $redis = Connect_redis::get();
}else{
    $redis = null;
}

use core\library\Curl\Curl as Curl;
//实例化curl-post数据传输类库
$curl_post = new Curl;

use core\library\Curl_get\Curl_get as Curl_get;
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

/**
 * 构造公共变量数组,用于控制器中获取外部公共变量
 */
$public_var = [
    'mysql_conn'=>$mysql_conn,
    'mysql_orm'=>$mysql_orm,
    'redis'=>$redis,
    'curl_post'=>$curl_post,
    'curl_get'=>$curl_get,
    'headers_message'=>$headers_message,
    'request'=>$request,
    'query_param'=>$query_param,
    'query_get'=>$query_get,
    'excel'=>$excel
];

//预加载model模型、logic逻辑文件,提取$response数据;
if(file_exists('..'.D.'..'.D.'model'.D.$query_model.'.php')){
    include('..'.D.'..'.D.'model'.D.$query_model.'.php');
}else{
    if(file_exists('..'.D.'..'.D.APPLICATION_RENAME[0].'_model'.D.$query_model.'.php')){
        include('..'.D.'..'.D.APPLICATION_RENAME[0].'_model'.D.$query_model.'.php');
    }else{
        exit("$query_model 模型不存在!");
    }
}
if(file_exists('..'.D.'logic'.D.$query_model.'.logic.php')){
    include('..'.D.'logic'.D.$query_model.'.logic.php');
}
if(file_exists('..'.D.APPLICATION_RENAME[4].'_logic'.D.$query_model.'.logic.php')){
    include('..'.D.APPLICATION_RENAME[4].'_logic'.D.$query_model.'.logic.php');
}

//加载API数据返回运行类库并调用
class Response{
    public static function data($response){
        include(CORE_PATH.'base_api.php');
        global $ob_inter;
        BaseApi::api_run($ob_inter,$response);
    }
}
function response($code=null,$msg=null,$data=null){
    if($code===null && $msg===null && $data===null){
        global $response;
        @Response::data($response);
    }else{
        $response = [$code,$msg,$data];
        @Response::data($response);
    }
}

//根据路由运行控制器
if(file_exists($query_controller.'.php') && $query_controller!='index'){
    include($query_controller.'.php');
    if(class_exists($query_controller,false)){
        $controller_ob = new $query_controller;
    }else {
        exit("未在控制器中找到 $query_controller 类!");
    }
    if(method_exists($controller_ob,$query_model)){
        $controller_ob->$query_model($public_var);
    }else{
        exit("未在控制器中找到 $query_model 类方法!");
    }
}elseif($query_controller=='index'){
    new Index();
}else{
    exit('API路由配置有误!');
}


