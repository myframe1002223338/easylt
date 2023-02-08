<?php
namespace core\library\Inter;
interface Api{
    public function state($code,$msg,$data);
}
class Inter implements Api{
    public function state($code,$msg,$data){
        $arr = ['code'=>$code,'msg'=>$msg,'data'=>$data];
        $json = json_encode($arr,256+64);
        $code_type = gettype($code);
        $msg_type = gettype($msg);
        if($code_type=='integer' && $msg_type=='string'){
            echo $json;
        }else{
            echo '出参返回的code或message数据类型有误！';
        }
    }
}

