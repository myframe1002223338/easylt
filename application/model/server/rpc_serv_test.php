<?php
include('server.ini');
$rpc_serv = new Rpc_server;
$rpc_serv->receive(function($function_name,$function_param){
    switch($function_name){
        case 'func1':
            $name = $function_param[0];
            $old_num = $function_param[1];
            $int = intval($old_num);
            $num = $int + 20;
            $result = $name.'一共有'.$num.'颗糖果';
            return $result;
        break;
        case 'func2':
            $name = $function_param[0];
            $old_num = $function_param[1];
            $int = intval($old_num);
            $num = $int + 10;
            $result = $name.'一共有'.$num.'颗糖果';
            return $result;
        break;
        default: return '方法调用失败';
    }
});
$rpc_serv->start();