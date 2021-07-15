<?php
namespace core;
function route(){
	$GLOBALS['query'] = explode('/',$_SERVER['REQUEST_URI']);
    //var_dump($GLOBALS['query']);
	//请在config_route.php中配置route参数
    $GLOBALS['query_model'] = strtolower($GLOBALS['query'][OS_MODEL]);
    $GLOBALS['query_param'] = strtolower($GLOBALS['query'][OS_PARAM]);
}