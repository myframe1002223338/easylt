<?php
namespace core\static_formwork\Curl;
class Curl{
    public function post($url,$data){
        $cu = curl_init();
        curl_setopt($cu,CURLOPT_URL,$url);
        curl_setopt($cu,CURLOPT_RETURNTRANSFER,POST_RETURNTRANSFER);
        curl_setopt($cu,CURLOPT_POST,1);
        if(is_array($data)){
            $data = json_encode($data,256+64);
        }
        curl_setopt($cu,CURLOPT_POSTFIELDS,$data);
        curl_setopt($cu,CURLOPT_SSL_VERIFYPEER,POST_SSL_VERIFYPEER);
        curl_setopt($cu,CURLOPT_SSL_VERIFYHOST,POST_SSL_VERIFYHOST);
        if(POST_SSL_VERIFYPEER===1){
            curl_setopt($cu,CURLOPT_CAINFO,POST_CAINFO);
        }
        curl_setopt($cu,CURLOPT_HEADER,POST_HEADER);
        curl_setopt($cu,CURLOPT_TIMEOUT,POST_TIMEOUT);
        $output = curl_exec($cu);
        curl_close($cu);
        return $output;
    }
}