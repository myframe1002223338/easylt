<?php
include('mysql_test.php');
//该model没有对应的logic文件时,请参考以下数据出参回传代码;如含有对应的logic文件,则复制以下数据回传代码到logic文件中;
// if($data && $data!='unconnect'){
//     $logic_name = '数据库连接成功！数据库读取值为'.$data[0]['content'].'，logic逻辑处理后值为';
//     $num = 5;
//     $new_data = $logic_name.($data[0]['content']+$num);
//     $response = ['code'=>200,'msg'=>'response success!','data'=>$new_data];
// }elseif($data=='unconnect'){
//     $response = ['code'=>403,'msg'=>'connect database error!','data'=>'测试失败！数据库无法连接！'];
// }elseif($data==null){
//     $response = ['code'=>400,'msg'=>'request failed!','data'=>'请求失败！请检查入参字段、值正确性！'];
// }