<?php
class Curl{
    public function post($url,$data,$headers=null,$status=null){
        $cu = curl_init();
        curl_setopt($cu,CURLOPT_URL,$url);
        curl_setopt($cu,CURLOPT_RETURNTRANSFER,POST_RETURNTRANSFER);
        curl_setopt($cu,CURLOPT_POST,1);
        if(is_array($data)){//如果数据为数组则自动转换为json或get-uri参数格式
            if($status=='json'){
                $data = json_encode($data,256+64);
            }elseif($status=='form'){
                $data = http_build_query($data);
            }else{
                $data = json_encode($data,256+64);
            }
        }
        curl_setopt($cu,CURLOPT_POSTFIELDS,$data);
        curl_setopt($cu,CURLOPT_SSL_VERIFYPEER,POST_SSL_VERIFYPEER);
        curl_setopt($cu,CURLOPT_SSL_VERIFYHOST,POST_SSL_VERIFYHOST);
        if(POST_SSL_VERIFYPEER===1){
            curl_setopt($cu,CURLOPT_CAINFO,POST_CAINFO);
        }
        if(is_array($headers)){
            curl_setopt($cu,CURLOPT_HTTPHEADER,$headers);
        }
        curl_setopt($cu,CURLOPT_HEADER,POST_HEADER);
        curl_setopt($cu,CURLOPT_TIMEOUT,POST_TIMEOUT);
        $output = curl_exec($cu);
        curl_close($cu);
        return $output;
    }
}
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
