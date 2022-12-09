<?php
namespace core\static_formwork\Curl_get;
class Curl_get{
    public function get($url,$headers=null){
        $cu = curl_init();
        curl_setopt($cu,CURLOPT_URL,$url);
        curl_setopt($cu,CURLOPT_RETURNTRANSFER,GET_RETURNTRANSFER);
        curl_setopt($cu,CURLOPT_SSL_VERIFYPEER,GET_SSL_VERIFYPEER);
        curl_setopt($cu,CURLOPT_SSL_VERIFYHOST,GET_SSL_VERIFYHOST);
        if(POST_SSL_VERIFYPEER===1){
            curl_setopt($cu,CURLOPT_CAINFO,GET_CAINFO);
        }
        if(is_array($headers)){
            curl_setopt($cu,CURLOPT_HTTPHEADER,$headers);
        }
        curl_setopt($cu,CURLOPT_HEADER,GET_HEADER);
        curl_setopt($cu,CURLOPT_TIMEOUT,GET_TIMEOUT);
        $output = curl_exec($cu);
        curl_close($cu);
        return $output;
    }
}