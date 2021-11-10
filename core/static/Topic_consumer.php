<?php
use PhpAmqpLib\Connection\AMQPStreamConnection;
class Topic_consumer{
    public $connection;
    public $channel;
    public $exchange;
    public $queue;
    public $topic;
    private function __clone(){
        // TODO: Implement __clone() method.
    }
    public function __construct($v_host,$exchange,$topic,$queue=null){
        $this->exchange = $exchange;
        $this->topic = $topic;
        if(php_sapi_name()!=='cli'){
            exit('该服务只能运行在cli模式下');
        }
        $this->connection = new AMQPStreamConnection(RABBITMQ_IP,RABBITMQ_PORT,RABBITMQ_USER,RABBITMQ_PWD,$v_host) or die('连接失败，请检查RabbitMQ服务是否启动！');
        $this->channel = $this->connection->channel();
        $this->channel->exchange_declare($this->exchange,'topic',false,false,false);
        if(RABBITMQ_TOPIC_FOREVER===1){
            list($this->queue,,) = $this->channel->queue_declare('',false,false,true,false);
        }else{
            $this->queue = $queue;
            $this->channel->queue_declare($this->queue,false,true,false,false);
        }
        $this->channel->queue_bind($this->queue,$this->exchange,$this->topic);
    }
    public function pop($func){
        $callback = function($msg)use($func){
            $consume_result = $func($msg->body);
            if($consume_result===true){
                $msg->ack();
            }
        };
        $this->channel->basic_qos(null,RABBITMQ_TOPIC_POP_NUM,null);
        $this->channel->basic_consume($this->queue,'',false,false,false,false,$callback);
        if(RABBITMQ_TOPIC_WAIT_MODEL===1){
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
        unset($this->topic);
    }
}