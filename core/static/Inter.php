<?php
namespace core\static_formwork\Inter;
interface Api{
    public function state($code,$msg,$data);
}
class Inter implements Api{
    public function state($code,$msg,$data){
        $arr = ['code'=>$code,'msg'=>$msg,'data'=>$data];
        $json = json_encode($arr,256+64);
        echo $json;
    }
}

