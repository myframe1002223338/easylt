<?php
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire\AMQPTable;
class Dead_producter{
    public $connection;
    public $channel;
    public $exchange;
    public $dead_exchange;
    public $rouing;
    public $dead_routing;
    public $queue;
    public $dead_queue;
    private function __clone(){//禁用克隆模式
        // TODO: Implement __clone() method.
    }
    public function connect($v_host,$exchange,$dead_exchange,$routing,$dead_routing,$queue,$dead_queue,$ttl){
        $this->exchange = $exchange;
        $this->dead_exchange = $dead_exchange;
        $this->routing = $routing;
        $this->dead_routing = $dead_routing;
        $this->queue = $queue;
        $this->dead_queue = $dead_queue;
        $this->connection = new AMQPStreamConnection(RABBITMQ_IP,RABBITMQ_PORT,RABBITMQ_USER,RABBITMQ_PWD,$v_host);
        $this->channel = $this->connection->channel();
        try{
            $this->channel->exchange_declare($this->exchange,'direct',false,false,false);
            $param = new AMQPTable(['x-message-ttl'=>$ttl,'x-dead-letter-exchange'=>$this->dead_exchange,'x-dead-letter-routing-key'=>$this->dead_routing]);
            $this->channel->queue_declare($this->queue,false,true,false,false,false,$param);
            $this->channel->queue_bind($this->queue,$this->exchange,$this->routing);
            $this->channel->exchange_declare($this->dead_exchange,'direct',false,false,false);
            $this->channel->queue_declare($this->dead_queue,false,true,false,false);
            $this->channel->queue_bind($this->dead_queue,$this->dead_exchange,$this->dead_routing);
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function push($func){
        $data = $func();
        if($data!=null){
            $msg = new AMQPMessage($data,['delivery_mode'=>AMQPMessage::DELIVERY_MODE_PERSISTENT]);
            $this->channel->basic_publish($msg,$this->exchange,$this->routing); 
        }
    }
    public function __destruct(){
        $this->channel->close();
        $this->connection->close();
        unset($this->connection);
        unset($this->channel);
        unset($this->exchange);
        unset($this->dead_exchange);
        unset($this->routing);
        unset($this->dead_routing);
        unset($this->queue);
        unset($this->dead_queue);
    }
}