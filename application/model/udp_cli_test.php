<?php
$udp_cli = new udp_client;
$udp_cli->connect();
$udp_cli->send(function(){
    global $mysql_orm;
    $data = $mysql_orm->model('select')->from('content,swoole')->where('username=&$swoole&')->query();
    return $data;
});
$udp_cli->recv(function($response_data){
    $GLOBALS['data'] = $response_data;
});
$response = ['code'=>200,'msg'=>'response success!','data'=>$GLOBALS['data']];