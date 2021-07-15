<?php
if($data && $data!='unconnect'){
    $logic_name = '数据库连接成功！数据库读取值为'.$data[0]['content'].'，logic逻辑处理后值为';
    $num = 5;
    $new_data = $logic_name.($data[0]['content']+$num);
    $response = ['code'=>200,'msg'=>'response success!','data'=>$new_data];
}elseif($data=='unconnect'){
    $response = ['code'=>403,'msg'=>'connect database error!','data'=>'测试失败！数据库无法连接！'];
}elseif($data==null){
    $response = ['code'=>400,'msg'=>'request failed!','data'=>'请求失败！请检查入参字段、值正确性！'];
}