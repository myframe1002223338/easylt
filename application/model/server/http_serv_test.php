<?php  
include('server.ini');
$http_serv = new http_server;
$http_serv->create();
$http_serv->request(function($request_post,$request_get){
    return $request_post;
});
$http_serv->start();