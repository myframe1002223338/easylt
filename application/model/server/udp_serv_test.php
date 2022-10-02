<?php
include('server.ini');
$udp_serv = new Udp_server;
$udp_serv->receive(function($request_data){
    return $request_data;
});
$udp_serv->start();