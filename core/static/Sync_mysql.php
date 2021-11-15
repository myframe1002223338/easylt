<?php
//同步mysql,内存常驻运行模式下不可采用单例模式;
class Sync_mysql{
    public static function co($func){
        $sync_mysql_conn = mysqli_connect(DB_HOST,DB_USER,DB_PWD);
        mysqli_set_charset($sync_mysql_conn,DB_CHARSET);
        mysqli_select_db($sync_mysql_conn,DB_NAME);
        if(!$sync_mysql_conn){
            $error = date('y-m-d h:i:s',time()).mysqli_connect_error($sync_mysql_conn).PHP_EOL;
            error_log($error,3,'mysql_connect_errors.log');
        }
        $mysql_orm = new Orm_sync;
        $func($sync_mysql_conn,$mysql_orm);
    }
}
class Connect_mysql_sync{
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
class Orm_sync extends Connect_mysql_sync{
    public function db($sql){
        for($i=0;$i<4;$i++){
            switch($i){//以下为原生DB操作
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
                if($where_result==1){//删除数据必须设置条件,否则删除失败
                    $result = mysqli_query($this->mysql_conn,$sql);
                    if(!$result){
                        $error = date('y-m-d h:i:s',time()).mysqli_error($this->mysql_conn).PHP_EOL;
                        error_log($error,3,'mysql_errors.log');
                    }
                    $affected = mysqli_affected_rows($this->mysql_conn);
                    if($affected>0){//删除操作要返回受影响的行,直接返回$result即使根据条件没有找到删除行也会返回布尔值为true导致事务执行异常;
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
                if($where_result==1){//更新数据必须设置条件,否则更新失败
                    $result = mysqli_query($this->mysql_conn,$sql);
                    if(!$result){
                        $error = date('y-m-d h:i:s',time()).mysqli_error($this->mysql_conn).PHP_EOL;
                        error_log($error,3,'mysql_errors.log');
                    }
                    $affected = mysqli_affected_rows($this->mysql_conn);
                    if($affected>0){//更新操作要返回受影响的行,直接返回$result即使根据条件没有找到更新行也会返回布尔值为true导致事务执行异常;
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
            case 'select':return new SELECT_sync($this->mysql_conn);
                break;
            case 'insert':return new INSERT_sync($this->mysql_conn);
                break;
            case 'delete':return new DELETE_sync($this->mysql_conn);
                break;
            case 'update':return new UPDATE_sync($this->mysql_conn);
                break;
        }
    }
}
//以下为非原生DB操作
class SELECT_sync{
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
        $field = explode('&',$arr[0]);//将条件字符串通过&分割符转化为数组
        $count = count($field);
        $arr_field  = [];
        for($i=0;$i<$count;$i++){//给每组元素添加逗号分隔符
            $arr_field[] = $field[$i].",";
        }
        $field = implode($arr_field);//将数组转回字符串
        $field = rtrim($field,',');//去除数组中最右侧多余的逗号分隔符
        $this->select_from = "select $field from $arr[1]";
        return $this;
    }
    public function where($where){
        $where = explode('&',$where);//将条件字符串通过&分割符转化为数组
        $count = count($where);
        $arr = [];
        for($i=0;$i<$count;$i++){
            if(strpos($where[$i],'$')===0){//当数组元素含$时,对条件字段值进行转义并添加单引号
                $new_param = mysqli_real_escape_string($this->mysql_conn,ltrim($where[$i],'$'));
                $arr[] = "'".$new_param."'";
            }else{
                $arr[] = $where[$i];
            }
        }
        $where = implode($arr);//将数组转回字符串
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
        if(strpos($param,',')){//如果没有逗号分隔符,说明没有传入offset参数
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
class INSERT_sync{
    public $insert_table;
    public $insert_values;
    public $insert_select;
    public $mysql_conn;
    public function __construct($mysql_conn){
        $this->mysql_conn = $mysql_conn;
    }
    public function table($param){
        $arr = explode(',',$param);
        $field = explode('&',$arr[0]);//将条件字符串通过&分割符转化为数组
        $count = count($field);
        $arr_field  = [];
        for($i=0;$i<$count;$i++){//给每组元素添加逗号分隔符
            $arr_field[] = $field[$i].",";
        }
        $field = implode($arr_field);//将数组转回字符串
        $field = rtrim($field,',');//去除数组中最右侧多余的逗号分隔符
        $this->insert_table = "insert into $arr[1]($field)";
        return $this;
    }
    public function values($value){
        $value = explode('&',$value);//将条件字符串通过&分割符转化为数组
        $count = count($value);
        $arr = [];
        for($i=0;$i<$count;$i++){//将一维数组转化为二维数组
            $arr[] =  explode(',',$value[$i]);
        }
        foreach($arr as $key => $value){//添加引号与逗号分隔符
            foreach($value as $k => $v){
                $a[$k] = "'".$v."',";
                $arr[$key] = $a;
            }
        }
        $trim_arr = [];
        foreach($arr as $k=>$v){//去除数组中最右侧多余的逗号分隔符
            $trim_arr[] = rtrim(implode($v),',');
        }
        $new_arr = [];
        for($i=0;$i<$count;$i++){//给数组添加括号与逗号分隔符
            $new_arr[] = '('.$trim_arr[$i].'),';
        }
        $value = implode($new_arr);//将数组转回字符串
        $value = rtrim($value,',');//去除字符串最右侧多余的逗号分隔符
        $this->insert_values = "values $value";
        return $this;
    }
    public function select($sql){
        $sql = explode('&',$sql);//将条件字符串通过&分割符转化为数组
        $count = count($sql);
        $arr = [];
        for($i=0;$i<$count;$i++){
            if(strpos($sql[$i],'$')===0){//当数组元素含$时,对条件字段值进行转义并添加单引号
                $new_param = mysqli_real_escape_string($this->mysql_conn,ltrim($sql[$i],'$'));
                $arr[] = "'".$new_param."'";
            }else{
                $arr[] = $sql[$i];
            }
        }
        $sql = implode($arr);//将数组转回字符串
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
class DELETE_sync{
    public $delete_table;
    public $delete_where;
    public $where_param;//用于判断条件字符串是否符合规则,防止随意输入清空整张表
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
        $where = explode('&',$where);//将条件字符串通过&分割符转化为数组
        $count = count($where);
        $arr = [];
        for($i=0;$i<$count;$i++){
            if(strpos($where[$i],'$')===0){//当数组元素含$时,对条件字段值进行转义并添加单引号
                $new_param = mysqli_real_escape_string($this->mysql_conn,ltrim($where[$i],'$'));
                $arr[] = "'".$new_param."'";
            }else{
                $arr[] = $where[$i];
            }
        }
        $where = implode($arr);//将数组转回字符串
        $this->delete_where = "where $where";
        return $this;
    }
    public function query(){
        $sql = $this->delete_table.PHP_EOL.$this->delete_where;
        if($this->delete_where && $this->where_param==1){//删除数据必须设置条件,否则删除失败
            $result = mysqli_query($this->mysql_conn,$sql);
            if(!$result){
                $error = date('y-m-d h:i:s',time()).mysqli_error($this->mysql_conn).PHP_EOL;
                error_log($error,3,'mysql_errors.log');
            }
            $affected = mysqli_affected_rows($this->mysql_conn);
            if($affected>0){//删除操作要返回受影响的行,直接返回$result即使根据条件没有找到删除行也会返回布尔值为true导致事务执行异常;
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
class UPDATE_sync{
    public $update_table;
    public $update_set;
    public $update_where;
    public $where_param;//用于判断条件字符串是否符合规则,防止随意输入更改整张表
    public $mysql_conn;
    public function __construct($mysql_conn){
        $this->mysql_conn = $mysql_conn;
    }
    public function table($table){
        $this->update_table = "update $table";
        return $this;
    }
    public function set($value){
        $value = explode('&',$value);//将条件字符串通过&分割符转化为数组
        $count = count($value);
        $arr = [];
        for($i=0;$i<$count;$i++){
            if(strpos($value[$i],'$')===0){//当数组元素含$时,对条件字段值添加单引号
                $new_param = ltrim($value[$i],'$');
                $arr[] = "'".$new_param."'";
            }else{
                $arr[] = $value[$i];
            }
        }
        $value = implode($arr);//将数组转回字符串
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
        $where = explode('&',$where);//将条件字符串通过&分割符转化为数组
        $count = count($where);
        $arr = [];
        for($i=0;$i<$count;$i++){
            if(strpos($where[$i],'$')===0){//当数组元素含$时,对条件字段值进行转义并添加单引号
                $new_param = mysqli_real_escape_string($this->mysql_conn,ltrim($where[$i],'$'));
                $arr[] = "'".$new_param."'";
            }else{
                $arr[] = $where[$i];
            }
        }
        $where = implode($arr);//将数组转回字符串
        $this->update_where = "where $where";
        return $this;
    }
    public function query(){
        $sql = $this->update_table.PHP_EOL.$this->update_set.PHP_EOL.$this->update_where;
        if($this->update_where && $this->where_param==1){//更新数据必须设置条件,否则更新失败
            $result = mysqli_query($this->mysql_conn,$sql);
            if(!$result){
                $error = date('y-m-d h:i:s',time()).mysqli_error($this->mysql_conn).PHP_EOL;
                error_log($error,3,'mysql_errors.log');
            }
            $affected = mysqli_affected_rows($this->mysql_conn);
            if($affected>0){//更新操作要返回受影响的行,直接返回$result即使根据条件没有找到更新行也会返回布尔值为true导致事务执行异常;
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

