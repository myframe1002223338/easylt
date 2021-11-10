<?php
/**
 * RabbitMQ连接配置
 */
//配置连接地址,默认127.0.0.1
define('RABBITMQ_IP','127.0.0.1');
//配置端口号,默认5672;
define('RABBITMQ_PORT',5672);
//配置连接用户名
define('RABBITMQ_USER','');
//配置连接密码
define('RABBITMQ_PWD','');

/**
 * RabbitMQ-simple模式配置
 */
//配置消费模式,1为等待模式,即消费者会持续消费消息并等待生产者生产消息;0为即刻模式,即消费者运行一次消费1条消息;
define('RABBITMQ_SIMPLE_WAIT_MODEL',1);
//配置一次消费消息最大值,默认1,即一次消费1条消息,用于削峰控制;仅在RABBITMQ_SIMPLE_WAIT_MODEL配置为1时生效,否则默认为1;
define('RABBITMQ_SIMPLE_POP_NUM',1);

/**
 * RabbitMQ-fanout模式配置
 */
//配置消费模式,1为等待模式,即消费者会持续消费消息并等待生产者生产消息;0为即刻模式,即消费者运行一次消费1条消息;
define('RABBITMQ_FANOUT_WAIT_MODEL',1);
//配置一次消费消息最大值,默认1,即一次消费1条消息,用于削峰控制;仅在RABBITMQ_FANOUT_WAIT_MODEL配置为1时生效,否则默认为1;
define('RABBITMQ_FANOUT_POP_NUM',1);
//配置消息队列持久化,1为临时消息队列,即消费者进程关闭后消息队列自动消失;0为持久化模式;
define('RABBITMQ_FANOUT_FOREVER',1);

/**
 * RabbitMQ-routing模式配置
 */
//配置消费模式,1为等待模式,即消费者会持续消费消息并等待生产者生产消息;0为即刻模式,即消费者运行一次消费1条消息;
define('RABBITMQ_ROUTING_WAIT_MODEL',1);
//配置一次消费消息最大值,默认1,即一次消费1条消息,用于削峰控制;仅在RABBITMQ_ROUTING_WAIT_MODEL配置为1时生效,否则默认为1;
define('RABBITMQ_ROUTING_POP_NUM',1);
//配置消息队列持久化,1为临时消息队列,即消费者进程关闭后消息队列自动消失;0为持久化模式;
define('RABBITMQ_ROUTING_FOREVER',1);

/**
 * RabbitMQ-topic模式配置
 */
//配置消费模式,1为等待模式,即消费者会持续消费消息并等待生产者生产消息;0为即刻模式,即消费者运行一次消费1条消息;
define('RABBITMQ_TOPIC_WAIT_MODEL',1);
//配置一次消费消息最大值,默认1,即一次消费1条消息,用于削峰控制;仅在RABBITMQ_TOPIC_WAIT_MODEL配置为1时生效,否则默认为1;
define('RABBITMQ_TOPIC_POP_NUM',1);
//配置消息队列持久化,1为临时消息队列,即消费者进程关闭后消息队列自动消失;0为持久化模式;
define('RABBITMQ_TOPIC_FOREVER',1);

/**
 * RabbitMQ-dead死信队列模式配置
 */
//配置消费模式,1为等待模式,即消费者会持续消费消息并等待生产者生产消息;0为即刻模式,即消费者运行一次消费1条消息;
define('RABBITMQ_DEAD_WAIT_MODEL',1);
//配置一次消费消息最大值,默认1,即一次消费1条消息,用于削峰控制;仅在RABBITMQ_DEAD_WAIT_MODEL配置为1时生效,否则默认为1;
define('RABBITMQ_DEAD_POP_NUM',1);






