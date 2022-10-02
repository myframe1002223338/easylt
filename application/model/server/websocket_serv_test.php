<?php
include('server.ini');
$websocket_serv = new websocket_server;
$websocket_serv->receive(function($request_data){
    Async_mysql::co(function($mysql_conn,$mysql_orm){
        $sql = "select username from src";
        $result = mysqli_query($mysql_conn,$sql);
        $assoc = mysqli_fetch_assoc($result);
        mysqli_free_result($result);
        mysqli_close($mysql_conn);
        $GLOBALS['data'] = $assoc['username'];
    });
    if($GLOBALS['data']){
        return "客户端发送数据：".$request_data.'<br />'."查询到的数据为：".$GLOBALS['data'].'<br />'."发送随机数据为：".mt_rand(1,999);
    }
});
$websocket_serv->start();
