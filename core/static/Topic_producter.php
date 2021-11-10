<?php
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
class Topic_producter{
    public $connection;
    public $channel;
    public $exchange;
    public $topic;
    private function __clone(){
        // TODO: Implement __clone() method.
    }
    public function __construct($v_host,$exchange,$topic){
        $this->exchange = $exchange;
        $this->topic = $topic;
        $this->connection = new AMQPStreamConnection(RABBITMQ_IP,RABBITMQ_PORT,RABBITMQ_USER,RABBITMQ_PWD,$v_host);
        $this->channel = $this->connection->channel();
        $this->channel->exchange_declare($this->exchange,'topic',false,false,false);
    }
    public function push($func){
        $data = $func();
        if($data!=null){
            $msg = new AMQPMessage($data,['delivery_mode'=>AMQPMessage::DELIVERY_MODE_PERSISTENT]);
            $this->channel->basic_publish($msg,$this->exchange,$this->topic);
        }
    }
    public function __destruct(){
        $this->channel->close();
        $this->connection->close();
        unset($this->connection);
        unset($this->channel);
        unset($this->exchange);
        unset($this->topic);
    }
}