<?php
use core\static_formwork\Connect_mysql\Connect_mysql as Connect_mysql;
class Orm extends Connect_mysql{
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
                $result = mysqli_query(Connect_mysql::get(),$sql);
                if(!$result){
                    $error = date('y-m-d h:i:s',time()).mysqli_error(Connect_mysql::get()).PHP_EOL;
                    error_log($error,3,'mysql_errors.log');
                }
                $arr = [];
                while($assoc=mysqli_fetch_assoc($result)){
                    $arr[] = $assoc;
                }
                mysqli_free_result($result);
                mysqli_close(Connect_mysql::get());
                return $arr;
            }elseif($match_result==1 && $match[0]=='insert'){
                $result = mysqli_query(Connect_mysql::get(),$sql);
                if(!$result){
                    $error = date('y-m-d h:i:s',time()).mysqli_error(Connect_mysql::get()).PHP_EOL;
                    error_log($error,3,'mysql_errors.log');
                }
                $run_state = ['affected'=>mysqli_affected_rows(Connect_mysql::get()),'insert_id'=>mysqli_insert_id(Connect_mysql::get())];
                mysqli_close(Connect_mysql::get());
                return $run_state;
            }elseif($match_result==1 && $match[0]=='delete'){
                $pattern_where = '/(where)/i';
                $where_result = preg_match($pattern_where,$sql,$match);
                if($where_result==1){//删除数据必须设置条件,否则删除失败
                    $result = mysqli_query(Connect_mysql::get(),$sql);
                    if(!$result){
                        $error = date('y-m-d h:i:s',time()).mysqli_error(Connect_mysql::get()).PHP_EOL;
                        error_log($error,3,'mysql_errors.log');
                    }
                    $run_state = ['affected'=>mysqli_affected_rows(Connect_mysql::get())];
                }else{
                    $run_state = ['affected'=>null];
                }
                mysqli_close(Connect_mysql::get());
                return $run_state;
            }elseif($match_result==1 && $match[0]=='update'){
                $pattern_where = '/(where)/i';
                $where_result = preg_match($pattern_where,$sql,$match);
                if($where_result==1){//更新数据必须设置条件,否则更新失败
                    $result = mysqli_query(Connect_mysql::get(),$sql);
                    if(!$result){
                        $error = date('y-m-d h:i:s',time()).mysqli_error(Connect_mysql::get()).PHP_EOL;
                        error_log($error,3,'mysql_errors.log');
                    }
                    $run_state = mysqli_affected_rows(Connect_mysql::get());
                }else{
                    $run_state = ['affected'=>null];
                }
                mysqli_close(Connect_mysql::get());
                return $run_state;
            }
        }
    }
    public function model($model){
        switch($model){
            case 'select':return new SELECT;
                break;
            case 'insert':return new INSERT;
                break;
            case 'delete':return new DELETE;
                break;
            case 'update':return new UPDATE;
                break;
        }
    }
}
class SELECT extends Connect_mysql{
    public $select_from;
    public $select_where;
    public $select_order;
    public $select_limit;
    public $select_group;
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
                $new_param = mysqli_real_escape_string(Connect_mysql::get(),ltrim($where[$i],'$'));
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
    public function query(){
        $sql = $this->select_from.PHP_EOL.$this->select_where.PHP_EOL.$this->select_group.PHP_EOL.$this->select_order.PHP_EOL.$this->select_limit;
        $result = mysqli_query(Connect_mysql::get(),$sql);
        if(!$result){
            $error = date('y-m-d h:i:s',time()).mysqli_error(Connect_mysql::get()).PHP_EOL;
            error_log($error,3,'mysql_errors.log');
        }
        $arr = [];
        while($assoc=mysqli_fetch_assoc($result)){
            $arr[] = $assoc;
        }
        mysqli_free_result($result);
        mysqli_close(Connect_mysql::get());
        return $arr;
    }
}

class INSERT extends Connect_mysql{
    public $insert_table;
    public $insert_values;
    public $insert_select;
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
        function walk(&$var){
            $var = "'".$var."',";
        }
        for($i=0;$i<$count;$i++){
            array_walk($arr[$i],'walk');//数组回调处理,添加引号与逗号分隔符
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
                $new_param = mysqli_real_escape_string(Connect_mysql::get(),ltrim($sql[$i],'$'));
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
        $result = mysqli_query(Connect_mysql::get(),$sql);
        if(!$result){
            $error = date('y-m-d h:i:s',time()).mysqli_error(Connect_mysql::get()).PHP_EOL;
            error_log($error,3,'mysql_errors.log');
        }
        $run_state = ['affected'=>mysqli_affected_rows(Connect_mysql::get()),'insert_id'=>mysqli_insert_id(Connect_mysql::get())];
        mysqli_close(Connect_mysql::get());
        return $run_state;
    }
}

class DELETE extends Connect_mysql{
    public $delete_table;
    public $delete_where;
    public $where_param;//用于判断条件字符串是否符合规则,防止随意输入清空整张表
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
                $new_param = mysqli_real_escape_string(Connect_mysql::get(),ltrim($where[$i],'$'));
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
            $result = mysqli_query(Connect_mysql::get(),$sql);
            if(!$result){
                $error = date('y-m-d h:i:s',time()).mysqli_error(Connect_mysql::get()).PHP_EOL;
                error_log($error,3,'mysql_errors.log');
            }
            $run_state = ['affected'=>mysqli_affected_rows(Connect_mysql::get())];
        }else{
            $run_state = ['affected'=>null];
        }
        mysqli_close(Connect_mysql::get());
        return $run_state;
    }
}

class UPDATE extends Connect_mysql{
    public $update_table;
    public $update_set;
    public $update_where;
    public $where_param;//用于判断条件字符串是否符合规则,防止随意输入更改整张表
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
                $new_param = mysqli_real_escape_string(Connect_mysql::get(),ltrim($where[$i],'$'));
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
            $result = mysqli_query(Connect_mysql::get(),$sql);
            if(!$result){
                $error = date('y-m-d h:i:s',time()).mysqli_error(Connect_mysql::get()).PHP_EOL;
                error_log($error,3,'mysql_errors.log');
            }
            $run_state = mysqli_affected_rows(Connect_mysql::get());
        }else{
            $run_state = ['affected'=>null];
        }
        mysqli_close(Connect_mysql::get());
        return $run_state;
    }
}
