<?php
if($query_result['param']=='a'){
    $username = $query_result['username'];
    if($mysql_conn){
        if($username){
            //ORM操作数据库有两种写法,可组装或分组:

            //以下为组装写法:
            $data = $mysql_orm->model('select')->from('*,account')->where('username=&$'.$username.'&')->query();
         
            //以下为分组写法:
            /*
            $mysql_ob = $mysql_orm->model('select');
            $mysql_ob->from('*,account');
            $mysql_ob->where('username=&$'.$username.'&');
            $data = $mysql_ob->query();
            */

            //以下为mysql原生写法
            /*
            $username = mysqli_real_escape_string($mysql_conn,$query_result['username']);
            $sql = "select * from account where username='{$username}'";
            class Select_test{
                public function select_account(){
                    global $mysql_conn,$sql;
                    $result = mysqli_query($mysql_conn,$sql);
                    if(!$result){
                        $error = date('y-m-d h:i:s',time()).mysqli_error($mysql_conn).PHP_EOL;
                        error_log($error,3,'mysql_errors.log');
                    }
                    $arr = [];
                    while($assoc = mysqli_fetch_assoc($result)){
                        $arr[] = $assoc;
                    }
                    mysqli_free_result($result);
                    mysqli_close($mysql_conn);
                    return $arr;
                }
                public function __destruct(){
                    unset($mysql_conn,$sql);
                }
            }
            $ob_select = new Select_test;
            $data = $ob_select->select_account();
            */
        }
    }else{
        $data = 'unconnect';
    }
}elseif($query_result['param']=='b'){
    $data = null;
}


