<?php
namespace core\static_formwork\Connect_redis;
class Connect_redis{
    public static $fac = null;
    public static $con_redis;
    public static $redis_test;
    private function __clone(){//禁用克隆模式
        // TODO: Implement __clone() method.
    }
    final public function __construct(){
        self::$con_redis = new \Redis();
        self::$con_redis->connect(REDIS_HOST,REDIS_PORT);
        self::$con_redis->auth(REDIS_AUTH);
        self::$con_redis->select(REDIS_DBNAME);
        self::$con_redis->set('redis_test',1);
        self::$redis_test = self::$con_redis->get('redis_test');
        if(self::$redis_test==1){
            self::$fac = 1;//当redis连接成功后,设定$fac变量为1,代表已经连接成功
        }
    }
    final public function __destruct(){
        if(self::$fac==1){
            self::$fac = '连接成功';
        }
    }
    public static function get(){
        if(self::$fac===null){//当$fac为null时,实例化并连接redis
            new self;
        }
//        echo self::$fac;//打印连接状态
        return self::$con_redis;//返回redis连接
    }
}
