<?php
namespace core\static_formwork\Connect_redis;
class Connect_redis{
    public static $fac = null;
    public static $con_redis;
    public static $redis_test;
    private function __clone(){
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
            self::$fac = 1;
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
        return self::$con_redis;
    }
}
