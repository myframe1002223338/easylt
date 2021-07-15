<?php
$tcp_cli = new tcp_client;
$tcp_cli->connect();
$tcp_cli->send(function(){
    global $mysql_orm;
    $data = $mysql_orm->model('select')->from('content,swoole')->where('username=&$swoole&')->query();
    return $data;
});
$tcp_cli->recv(function($response_data){
    $GLOBALS['data'] = $response_data;
});
$tcp_cli->close();
$response = ['code'=>200,'msg'=>'response success!','data'=>$GLOBALS['data']];