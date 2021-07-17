<?php
include('server.ini');
$websocket_serv = new websocket_server;
$websocket_serv->create();
$websocket_serv->connect(function(){
    global $db;
    return $db;
});
$websocket_serv->receive(function($request_data){
    // return "客户端发送数据：".$request_data.'<br />'."发送随机数据为：".mt_rand(1,999);
});
$websocket_serv->workerstart(function($request_data,$mysql,$redis){
    $sql = "select username from swoole";
    $result = mysqli_query($mysql,$sql);
    $assoc = mysqli_fetch_assoc($result);
    return "客户端发送数据：".$request_data.'<br />'."查询到的数据为：".$assoc['username'].'<br />'
    ."发送随机数据为：".mt_rand(1,999);
});
$websocket_serv->close(function(){
});
$websocket_serv->start();
