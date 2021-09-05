<?php
class Process{
    public $mysql;
    public $redis;
    private function __clone(){//禁用克隆模式
        // TODO: Implement __clone() method.
    }
    public function create($func){
        $process = new swoole_process(function($process)use($func){
            $mysql_conn = mysqli_connect(DB_HOST,DB_USER,DB_PWD);//多进程直接引入外部mysql连接会出现问题,在这里实现连接;
            mysqli_set_charset($mysql_conn,DB_CHARSET);
            mysqli_select_db($mysql_conn,DB_NAME);
            $this->mysql = $mysql_conn;
            $redis_conn = new \Redis();//多进程直接引入外部redis连接会出现问题,在这里实现连接;
            $redis_conn->connect(REDIS_HOST,REDIS_PORT);
            $redis_conn->auth(REDIS_AUTH);
            $redis_conn->select(REDIS_DBNAME);
            $this->redis = $redis_conn;
            $func($process,$this->mysql,$this->redis);
        });
        if(PROCESS_QUEUE===1){
            $process->useQueue();
        }
        if(PROCESS_DAEMONIZE===1){
            swoole_process::daemon();
        }
        $process->start();
        Swoole\Event::wait();
    }
    public function __destruct(){
        unset($this->mysql);
        unset($this->redis);
    }
}