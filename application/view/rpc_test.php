<?php
$rpc_cli = new rpc_client;
$rpc_cli->connect();
$rpc_cli->send(function(){
    return 'func1(liteng,10)';//向RPC服务器发送数据:方法名为func1,实参为'liteng','10';
});
$rpc_cli->recv(function($response_data){
    $GLOBALS['data'] = $response_data;
});
$rpc_cli->close();
echo '接收到RPC服务器处理完并返回的数据：'.$GLOBALS['data'];