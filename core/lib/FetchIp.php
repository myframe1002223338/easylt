<?php
namespace core\lib\FetchIp;
//获取访问者IP
class FetchIp{
    public function get_client_ip(){
        $ip = $_SERVER['REMOTE_ADDR'];
        if (isset($_SERVER['HTTP_CLIENT_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CLIENT_IP'])){
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }elseif(isset($_SERVER['HTTP_X_FORWARDED_FOR']) AND preg_match_all('#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#s', $_SERVER['HTTP_X_FORWARDED_FOR'], $matches)){
            foreach ($matches[0] AS $xip){
                if (!preg_match('#^(10|172\.16|192\.168)\.#', $xip)){
                    $ip = $xip;
                    break;
                }
            }
        }
        return $ip;
    }
}

