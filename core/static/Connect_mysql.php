<?php
namespace core\static_formwork\Connect_mysql;
class Connect_mysql{
    public static $fac = null;
    public static $con;
    private function __clone(){
        // TODO: Implement __clone() method.
    }
    final public function __construct(){
        self::$con = mysqli_connect(DB_HOST,DB_USER,DB_PWD);
        if(self::$con){
            self::$fac = 1;
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
        if(self::$fac===null){
            new self;
        }
        return self::$con;
    }
}
