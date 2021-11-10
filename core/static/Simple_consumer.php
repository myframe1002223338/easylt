<?php
use PhpAmqpLib\Connection\AMQPStreamConnection;
class Simple_consumer{
    public $connection;
    public $channel;
    public $queue;
    private function __clone(){
        // TODO: Implement __clone() method.
    }
    public function __construct($v_host,$queue){
        $this->queue = $queue;
        $this->connection = new AMQPStreamConnection(RABBITMQ_IP,RABBITMQ_PORT,RABBITMQ_USER,RABBITMQ_PWD,$v_host);
        $this->channel = $this->connection->channel();
        $this->channel->queue_declare($this->queue,false,true,false,false);
    }
    public function pop($func){
        $callback = function($msg)use($func){
            $consume_result = $func($msg->body);
            if($consume_result===true){
                $msg->ack();
            }
        };
        $this->channel->basic_qos(null,RABBITMQ_SIMPLE_POP_NUM,null);
        $this->channel->basic_consume($this->queue,'',false,false,false,false,$callback);
        if(RABBITMQ_SIMPLE_WAIT_MODEL===1){
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
        unset($this->queue);
    }
}