<?php
namespace core\lib\CheckPort;
//判断端口是否被占用,$result为false则被占用,为ture则空闲;
class CheckPort{
    public function checking($host,$port){
        $socket = stream_socket_server("tcp://$host:$port");
        if(!$socket){
            return false;
        }
        fclose($socket);
        unset($socket);
        return true;
    }
}
