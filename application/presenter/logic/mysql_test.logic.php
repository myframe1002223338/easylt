<?php
/**
 * restful - API的请求回传状态根据 $response数据判断返回，如下：
 * $response - status:
 *             $response['data'] = 正常返回数据时 返回 code:200 message:response success!
 *             $response['data'] = null 返回 code:400 message:request failed!
 *             无法连接mysql数据库 返回 code:403 message:connect database error!
 *             更多$response返回状态码请自行设计添加;
 */
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
