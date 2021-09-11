<?php
use PhpAmqpLib\Connection\AMQPStreamConnection;
class Routing_consumer{
    public $connection;
    public $channel;
    public $exchange;
    public $queue;
    public $routing;
    private function __clone(){//禁用克隆模式
        // TODO: Implement __clone() method.
    }
    public function connect($v_host,$exchange,$routing,$queue=null){
        $this->exchange = $exchange;
        $this->routing = $routing;
        if(php_sapi_name()!=='cli'){
            exit('该服务只能运行在cli模式下');
        }
        $this->connection = new AMQPStreamConnection(RABBITMQ_IP,RABBITMQ_PORT,RABBITMQ_USER,RABBITMQ_PWD,$v_host);
        $this->channel = $this->connection->channel();
        $this->channel->exchange_declare($this->exchange,'direct',false,false,false);
        if(RABBITMQ_ROUTING_FOREVER===1){
            list($this->queue,,) = $this->channel->queue_declare('',false,false,true,false);
        }else{
            $this->queue = $queue;
            $this->channel->queue_declare($this->queue,false,true,false,false);
        }
        $this->channel->queue_bind($this->queue,$this->exchange,$this->routing);
    }
    public function pop($func){
        $callback = function($msg)use($func){
            $consume_result = $func($msg->body);
            if($consume_result===true){
                $msg->ack();
            }
        };
        $this->channel->basic_qos(null,RABBITMQ_ROUTING_POP_NUM,null);
        $this->channel->basic_consume($this->queue,'',false,false,false,false,$callback);
        if(RABBITMQ_ROUTING_WAIT_MODEL===1){
            while($this->channel->is_open()){
                $this->channel->wait();
            }
        }else{
            $this->channel->wait();
        }
    }
    public function __destruct(){
        $this->channel->close();
        $this->connection->close();
        unset($this->connection);
        unset($this->channel);
        unset($this->exchange);
        unset($this->queue);
        unset($this->routing);
    }
}