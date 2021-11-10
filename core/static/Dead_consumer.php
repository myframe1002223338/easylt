<?php
use PhpAmqpLib\Connection\AMQPStreamConnection;
class Dead_consumer{
    public $connection;
    public $channel;
    public $dead_exchange;
    public $dead_routing;
    public $dead_queue;
    private function __clone(){
        // TODO: Implement __clone() method.
    }
    public function __construct($v_host,$dead_exchange,$dead_routing,$dead_queue){
        $this->dead_exchange = $dead_exchange;
        $this->dead_routing = $dead_routing;
        $this->dead_queue = $dead_queue;
        if(php_sapi_name()!=='cli'){
            exit('该服务只能运行在cli模式下');
        }
        $this->connection = new AMQPStreamConnection(RABBITMQ_IP,RABBITMQ_PORT,RABBITMQ_USER,RABBITMQ_PWD,$v_host);
        $this->channel = $this->connection->channel();
        $this->channel->exchange_declare($this->dead_exchange,'direct',false,false,false);
        $this->channel->queue_bind($this->dead_queue,$this->dead_exchange,$this->dead_routing);
    }
    public function pop($func){
        $callback = function($msg)use($func){
            $consume_result = $func($msg->body);
            if($consume_result===true){
                $msg->ack();
            }
        };
        $this->channel->basic_qos(null,RABBITMQ_DEAD_POP_NUM,null);
        $this->channel->basic_consume($this->dead_queue,'',false,false,false,false,$callback);
        if(RABBITMQ_DEAD_WAIT_MODEL===1){
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
        unset($this->dead_exchange);
        unset($this->dead_routing);
        unset($this->dead_queue);
    }
}