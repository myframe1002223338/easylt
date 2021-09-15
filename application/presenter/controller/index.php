<?php
//加载配置文件
include('..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'core'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'config.php');
error_reporting(ERROR_STATE);
header('Content-type:text/html;charset=utf-8');
date_default_timezone_set('Asia/chongqing');
include('..'.D.'..'.D.'..'.D.'core'.D.'config'.D.'config_controller.php');
include('..'.D.'..'.D.'..'.D.'core'.D.'config'.D.'config_route.php');
include('..'.D.'..'.D.'..'.D.'core'.D.'config'.D.'config_swoole.php');
include('..'.D.'..'.D.'..'.D.'core'.D.'config'.D.'config_rabbitmq.php');

//动态加载类库
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
//实例化API数据返回输出类库
$ob_inter = new Inter;

use core\static_formwork\Connect_mysql\Connect_mysql as Connect_mysql;
//加载mysql连接类库
$mysql_conn = Connect_mysql::get();

//实例化mysql-ORM类库
$mysql_orm = new Orm;

use core\static_formwork\Connect_redis\Connect_redis as Connect_redis;
//加载redis连接类库,默认不加载,请在config.php中配置是否加载;
if(REDIS_INCLUDE==1){
    $redis = Connect_redis::get();
}

use core\static_formwork\Curl\Curl as Curl;
//实例化curl-post数据传输类库
$curl_post = new Curl;

use core\static_formwork\Curl_get\Curl_get as Curl_get;
//实例化curl-get数据传输类库
$curl_get = new Curl_get;

if(URL_ON_OFF==1){
    //设置API跨域参数(单域名或所有域名(*)配置)
    header('Access-Control-Allow-Origin:'.SINGLE_URL);
}else{
    //设置API跨域参数(多域名配置)
    foreach(ALL_URL as $v){
        header('Access-Control-Allow-Origin:'.$v);
    }
}

//验证头信息,默认不开启,请在config_controller.php中配置是否开启;
if(AUTH_ON_OFF==1){
    //头信息Authorization验证,不合法的请求将拒绝;
    $Authorization = [];
    foreach(getallheaders() as $key => $value){
        $Authorization[$key] = $value;
    }
    if($Authorization['Authorization']!==AUTH){
        $ob_inter->state('401','Authorization is wrong!','头信息验证失败！');
        die;
    }
}

//加载route解析方法获取route请求参数用于路由分发
include('..'.D.'..'.D.'..'.D.'core'.D.'route.php');
core\route();

class Index{
    public $route_param;
    public $json_value;
    public function catch_params(){
        global $query_param;
        $json = file_get_contents('php://input');
        $this->route_param = $query_param;
        if($json){
            $this->json_value = json_decode($json,true);
        }else{
            $this->json_value = ['init'=>null];//如果view为get请求,初始化数组用于以下array_merge,否则需要多种情况判断;
        }
        if($this->route_param){
            unset($query_param);
            return json_encode(array_merge(['param'=>$this->route_param],$this->json_value));
        }else{
            unset($query_param);
            return json_encode($this->json_value);
        }
    }
    public function __destruct(){
        unset($this->route_param);
        unset($this->json_value);
    }
}
$ob_index = new Index;
$request = $ob_index->catch_params();
$request = json_decode($request,true);

//预加载model模型、logic逻辑文件,提取$response数据;如果model模型文件含有对应的logic逻辑文件则加载model、logic文件,否则仅加载model文件;
if(include('..'.D.'logic'.D.$query_model.'.logic.php')){
	include('..'.D.'..'.D.'model'.D.$query_model.'.php');
    include('..'.D.'logic'.D.$query_model.'.logic.php');
}else{
	include('..'.D.'..'.D.'model'.D.$query_model.'.php');
}
//加载API数据返回运行类库
include('..'.D.'..'.D.'..'.D.'core'.D.'base.php');
AutoLoad::api_run($ob_inter,$response);



