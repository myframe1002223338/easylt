<?php
include('server.ini');
$udp_serv = new udp_server;
$udp_serv->create();
$udp_serv->packet(function($request_data){
    return $request_data;
});
$udp_serv->start();