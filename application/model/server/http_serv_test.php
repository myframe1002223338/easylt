<?php  
include('server.ini');
$http_serv = new Http_server;
$http_serv->receive(function($request_post,$request_get){
    return $request_post;
});
$http_serv->start();