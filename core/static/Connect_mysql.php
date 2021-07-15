<?php
namespace core\static_formwork\Connect_mysql;
class Connect_mysql{
    public static $fac = null;
    public static $con;
    private function __clone(){//禁用克隆模式
        // TODO: Implement __clone() method.
    }
    final public function __construct(){
        self::$con = mysqli_connect(DB_HOST,DB_USER,DB_PWD);
        if(self::$con){
            self::$fac = 1;//当数据库连接成功后,设定$fac变量为1,代表已经连接成功
            mysqli_set_charset(self::$con,DB_CHARSET);
            mysqli_select_db(self::$con,DB_NAME);
        }else{
            $error = date('y-m-d h:i:s',time()).mysqli_connect_error(self::$con).PHP_EOL;
            error_log($error,3,'mysql_connect_errors.log');
        }
    }
    final public function __destruct(){
        if(self::$fac==1){
            self::$fac = '连接成功';
        }
    }
    public static function get(){
        if(self::$fac===null){//当$fac为null时,实例化并连接数据库
            new self;
        }
//        echo self::$fac;//打印连接状态
        return self::$con;//返回数据库连接
    }
}
