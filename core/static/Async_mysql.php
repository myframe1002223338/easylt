<?php
use Swoole\Coroutine;
use function Swoole\Coroutine\run;
class Async_mysql{
    public function __construct($func){
        if(php_sapi_name()!=='cli'){
            exit('该服务只能运行在cli模式下');
        }
        if(!extension_loaded('swoole')){
            exit('请安装swoole扩展');
        }
        run(function()use($func){
            $func();
        });
    }
    public static function co($func,$sleep=null){
        if(php_sapi_name()!=='cli'){
            exit('该服务只能运行在cli模式下');
        }
        if(!extension_loaded('swoole')){
            exit('请安装swoole扩展');
        }
        Coroutine::create(function()use($func,$sleep){
            if($sleep!=null){
                sleep($sleep);
            }
            $async_mysql_conn = mysqli_connect(DB_HOST,DB_USER,DB_PWD);
            mysqli_set_charset($async_mysql_conn,DB_CHARSET);
            mysqli_select_db($async_mysql_conn,DB_NAME);
            if(!$async_mysql_conn){
                $error = date('y-m-d h:i:s',time()).mysqli_connect_error($async_mysql_conn).PHP_EOL;
                error_log($error,3,'mysql_connect_errors.log');
            }
            $mysql_orm = new Orm_async;
            $func($async_mysql_conn,$mysql_orm);
        });
    }
}
class Connect_mysql_async{
    public $mysql_conn;
    public function __construct(){
        $this->mysql_conn = mysqli_connect(DB_HOST,DB_USER,DB_PWD);
        mysqli_set_charset($this->mysql_conn,DB_CHARSET);
        mysqli_select_db($this->mysql_conn,DB_NAME);
        if(!$this->mysql_conn){
            $error = date('y-m-d h:i:s',time()).mysqli_connect_error($this->mysql_conn).PHP_EOL;
            error_log($error,3,'mysql_connect_errors.log');
        }
    }
    public function trans(){
        mysqli_query($this->mysql_conn,'SET AUTOCOMMIT=0');
        mysqli_begin_transaction($this->mysql_conn);
    }
    public function commit(){
        mysqli_commit($this->mysql_conn);
    }
    public function rollback(){
        mysqli_rollback($this->mysql_conn);
    }
    public function destruct(){
        mysqli_close($this->mysql_conn);
        unset($this->mysql_conn);
    }
}
class Orm_async extends Connect_mysql_async{
    public function db($sql){
        for($i=0;$i<4;$i++){
            switch($i){
                case 0:$pattern = '/(^select)/i';
                    break;
                case 1:$pattern = '/(^insert)/i';
                    break;
                case 2:$pattern = '/(^delete)/i';
                    break;
                case 3:$pattern = '/(^update)/i';
                    break;
            }
            $match_result = preg_match($pattern,$sql,$match);
            if($match_result==1 && $match[0]=='select'){
                $result = mysqli_query($this->mysql_conn,$sql);
                if(!$result){
                    $error = date('y-m-d h:i:s',time()).mysqli_error($this->mysql_conn).PHP_EOL;
                    error_log($error,3,'mysql_errors.log');
                }
                $arr = [];
                while($assoc=mysqli_fetch_assoc($result)){
                    $arr[] = $assoc;
                }
                mysqli_free_result($result);
                return $arr;
            }elseif($match_result==1 && $match[0]=='insert'){
                $result = mysqli_query($this->mysql_conn,$sql);
                if(!$result){
                    $error = date('y-m-d h:i:s',time()).mysqli_error($this->mysql_conn).PHP_EOL;
                    error_log($error,3,'mysql_errors.log');
                }
                $affected = mysqli_affected_rows($this->mysql_conn);
                return $affected;
            }elseif($match_result==1 && $match[0]=='delete'){
                $pattern_where = '/(where)/i';
                $where_result = preg_match($pattern_where,$sql,$match);
                if($where_result==1){
                    $result = mysqli_query($this->mysql_conn,$sql);
                    if(!$result){
                        $error = date('y-m-d h:i:s',time()).mysqli_error($this->mysql_conn).PHP_EOL;
                        error_log($error,3,'mysql_errors.log');
                    }
                    $affected = mysqli_affected_rows($this->mysql_conn);
                    if($affected>0){
                        return $affected;
                    }else{
                        return null;
                    }
                }else{
                    return null;
                }
            }elseif($match_result==1 && $match[0]=='update'){
                $pattern_where = '/(where)/i';
                $where_result = preg_match($pattern_where,$sql,$match);
                if($where_result==1){
                    $result = mysqli_query($this->mysql_conn,$sql);
                    if(!$result){
                        $error = date('y-m-d h:i:s',time()).mysqli_error($this->mysql_conn).PHP_EOL;
                        error_log($error,3,'mysql_errors.log');
                    }
                    $affected = mysqli_affected_rows($this->mysql_conn);
                    if($affected>0){
                        return $affected;
                    }else{
                        return null;
                    }
                }else{
                    return null;
                }
            }
        }
    }
    public function model($model){
        switch($model){
            case 'select':return new SELECT_async($this->mysql_conn);
                break;
            case 'insert':return new INSERT_async($this->mysql_conn);
                break;
            case 'delete':return new DELETE_async($this->mysql_conn);
                break;
            case 'update':return new UPDATE_async($this->mysql_conn);
                break;
        }
    }
}

class SELECT_async{
    public $select_from;
    public $select_where;
    public $select_order;
    public $select_limit;
    public $select_group;
    public $lock;
    public $mysql_conn;
    public function __construct($mysql_conn){
        $this->mysql_conn = $mysql_conn;
    }
    public function from($param){
        $arr = explode(',',$param);
        $field = explode('&',$arr[0]);
        $count = count($field);
        $arr_field  = [];
        for($i=0;$i<$count;$i++){
            $arr_field[] = $field[$i].",";
        }
        $field = implode($arr_field);
        $field = rtrim($field,',');
        $this->select_from = "select $field from $arr[1]";
        return $this;
    }
    public function where($where){
        $where = explode('&',$where);
        $count = count($where);
        $arr = [];
        for($i=0;$i<$count;$i++){
            if(strpos($where[$i],'$')===0){
                $new_param = mysqli_real_escape_string($this->mysql_conn,ltrim($where[$i],'$'));
                $arr[] = "'".$new_param."'";
            }else{
                $arr[] = $where[$i];
            }
        }
        $where = implode($arr);
        $this->select_where = "where $where";
        return $this;
    }
    public function group_by($field){
        $this->select_group = "group by $field";
        return $this;
    }
    public function order_by($param){
        $arr = explode(',',$param);
        $this->select_order = "order by $arr[0] $arr[1]";
        return $this;
    }
    public function limit($param){
        if(strpos($param,',')){
            $param = explode(',',$param);
            $this->select_limit = "limit $param[0],$param[1]";
        }else{
            $this->select_limit = "limit $param";
        }
        return $this;
    }
    public function lock($lock_mode){
        if($lock_mode=='write'){
            $this->lock = 'for update';
        }elseif($lock_mode=='read'){
            $this->lock = 'lock in share mode';
        }
        return $this;
    }
    public function query(){
        $sql = $this->select_from.PHP_EOL.$this->select_where.PHP_EOL.$this->select_group.PHP_EOL.$this->select_order.PHP_EOL.$this->select_limit.PHP_EOL.$this->lock;
        $result = mysqli_query($this->mysql_conn,$sql);
        if(!$result){
            $error = date('y-m-d h:i:s',time()).mysqli_error($this->mysql_conn).PHP_EOL;
            error_log($error,3,'mysql_errors.log');
        }
        $arr = [];
        while($assoc=mysqli_fetch_assoc($result)){
            $arr[] = $assoc;
        }
        mysqli_free_result($result);
        return $arr;
    }
    public function __destruct(){
        unset($this->select_from);
        unset($this->select_where);
        unset($this->select_order);
        unset($this->select_limit);
        unset($this->select_group);
        unset($this->lock);
        unset($this->mysql_conn);
    }
}
class INSERT_async{
    public $insert_table;
    public $insert_values;
    public $insert_select;
    public $mysql_conn;
    public function __construct($mysql_conn){
        $this->mysql_conn = $mysql_conn;
    }
    public function table($param){
        $arr = explode(',',$param);
        $field = explode('&',$arr[0]);
        $count = count($field);
        $arr_field  = [];
        for($i=0;$i<$count;$i++){
            $arr_field[] = $field[$i].",";
        }
        $field = implode($arr_field);
        $field = rtrim($field,',');
        $this->insert_table = "insert into $arr[1]($field)";
        return $this;
    }
    public function values($value){
        $value = explode('&',$value);
        $count = count($value);
        $arr = [];
        for($i=0;$i<$count;$i++){
            $arr[] =  explode(',',$value[$i]);
        }
        foreach($arr as $key => $value){
            foreach($value as $k => $v){
                $a[$k] = "'".$v."',";
                $arr[$key] = $a;
            }
        }
        $trim_arr = [];
        foreach($arr as $k=>$v){
            $trim_arr[] = rtrim(implode($v),',');
        }
        $new_arr = [];
        for($i=0;$i<$count;$i++){
            $new_arr[] = '('.$trim_arr[$i].'),';
        }
        $value = implode($new_arr);
        $value = rtrim($value,',');
        $this->insert_values = "values $value";
        return $this;
    }
    public function select($sql){
        $sql = explode('&',$sql);
        $count = count($sql);
        $arr = [];
        for($i=0;$i<$count;$i++){
            if(strpos($sql[$i],'$')===0){
                $new_param = mysqli_real_escape_string($this->mysql_conn,ltrim($sql[$i],'$'));
                $arr[] = "'".$new_param."'";
            }else{
                $arr[] = $sql[$i];
            }
        }
        $sql = implode($arr);
        $this->insert_select = "$sql";
        return $this;
    }
    public function query(){
        $sql = $this->insert_table.PHP_EOL.$this->insert_values.PHP_EOL.$this->insert_select;
        $result = mysqli_query($this->mysql_conn,$sql);
        if(!$result){
            $error = date('y-m-d h:i:s',time()).mysqli_error($this->mysql_conn).PHP_EOL;
            error_log($error,3,'mysql_errors.log');
        }
        $affected = mysqli_affected_rows($this->mysql_conn);
        return $affected;
    }
    public function __destruct(){
        unset($this->insert_table);
        unset($this->insert_values);
        unset($this->insert_select);
        unset($this->mysql_conn);
    }
}
class DELETE_async{
    public $delete_table;
    public $delete_where;
    public $where_param;
    public $mysql_conn;
    public function __construct($mysql_conn){
        $this->mysql_conn = $mysql_conn;
    }
    public function table($table){
        $this->delete_table = "delete from $table";
        return $this;
    }
    public function where($where){
        if(strpos($where,'=') || strpos($where,'!=') || strpos($where,'<') || strpos($where,'<=')
            || strpos($where,'>') || strpos($where,'>=') || strpos($where,'<>') || strpos($where,'like')
            || strpos($where,'regexp')){
            $this->where_param = 1;
        }else{
            $this->where_param = 0;
        }
        $where = explode('&',$where);
        $count = count($where);
        $arr = [];
        for($i=0;$i<$count;$i++){
            if(strpos($where[$i],'$')===0){
                $new_param = mysqli_real_escape_string($this->mysql_conn,ltrim($where[$i],'$'));
                $arr[] = "'".$new_param."'";
            }else{
                $arr[] = $where[$i];
            }
        }
        $where = implode($arr);
        $this->delete_where = "where $where";
        return $this;
    }
    public function query(){
        $sql = $this->delete_table.PHP_EOL.$this->delete_where;
        if($this->delete_where && $this->where_param==1){
            $result = mysqli_query($this->mysql_conn,$sql);
            if(!$result){
                $error = date('y-m-d h:i:s',time()).mysqli_error($this->mysql_conn).PHP_EOL;
                error_log($error,3,'mysql_errors.log');
            }
            $affected = mysqli_affected_rows($this->mysql_conn);
            if($affected>0){
                return $affected;
            }else{
                return null;
            }
        }else{
            return null;
        }
    }
    public function __destruct(){
        unset($this->delete_table);
        unset($this->delete_where);
        unset($this->where_param);
        unset($this->mysql_conn);
    }
}
class UPDATE_async{
    public $update_table;
    public $update_set;
    public $update_where;
    public $where_param;
    public $mysql_conn;
    public function __construct($mysql_conn){
        $this->mysql_conn = $mysql_conn;
    }
    public function table($table){
        $this->update_table = "update $table";
        return $this;
    }
    public function set($value){
        $value = explode('&',$value);
        $count = count($value);
        $arr = [];
        for($i=0;$i<$count;$i++){
            if(strpos($value[$i],'$')===0){
                $new_param = ltrim($value[$i],'$');
                $arr[] = "'".$new_param."'";
            }else{
                $arr[] = $value[$i];
            }
        }
        $value = implode($arr);
        $this->update_set = "set $value";
        return $this;
    }
    public function where($where){
        if(strpos($where,'=') || strpos($where,'!=') || strpos($where,'<') || strpos($where,'<=')
            || strpos($where,'>') || strpos($where,'>=') || strpos($where,'<>') || strpos($where,'like')
            || strpos($where,'regexp')){
            $this->where_param = 1;
        }else{
            $this->where_param = 0;
        }
        $where = explode('&',$where);
        $count = count($where);
        $arr = [];
        for($i=0;$i<$count;$i++){
            if(strpos($where[$i],'$')===0){
                $new_param = mysqli_real_escape_string($this->mysql_conn,ltrim($where[$i],'$'));
                $arr[] = "'".$new_param."'";
            }else{
                $arr[] = $where[$i];
            }
        }
        $where = implode($arr);
        $this->update_where = "where $where";
        return $this;
    }
    public function query(){
        $sql = $this->update_table.PHP_EOL.$this->update_set.PHP_EOL.$this->update_where;
        if($this->update_where && $this->where_param==1){
            $result = mysqli_query($this->mysql_conn,$sql);
            if(!$result){
                $error = date('y-m-d h:i:s',time()).mysqli_error($this->mysql_conn).PHP_EOL;
                error_log($error,3,'mysql_errors.log');
            }
            $affected = mysqli_affected_rows($this->mysql_conn);
            if($affected>0){
                return $affected;
            }else{
                return null;
            }
        }else{
            return null;
        }
    }
    public function __destruct(){
        unset($this->update_table);
        unset($this->update_set);
        unset($this->update_where);
        unset($this->where_param);
        unset($this->mysql_conn);
    }
}
