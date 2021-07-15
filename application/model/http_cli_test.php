<?php
$http_cli = new http_client;
$data = $mysql_orm->model('select')->from('content,swoole')->where('username=&$swoole&')->query();
$result =$http_cli->post($data);
$response = ['code'=>200,'msg'=>'response success!','data'=>$result];