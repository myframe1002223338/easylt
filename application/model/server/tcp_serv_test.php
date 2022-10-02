<?php  
include('server.ini');
$tcp_serv = new Tcp_server;
$tcp_serv->receive(function($request_data){
    return $request_data;
});
$tcp_serv->start();
