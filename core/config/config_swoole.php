<?php
/**
 * 配置TCP服务器
 */
//配置服务器开关,1为开启,0为关闭,关闭后服务器无法启动;
define('TCP_SERV_ON_OFF',1);
//配置服务器监听IP,默认0.0.0.0,同时监听多个IP;
define('TCP_SERV_IP','0.0.0.0');
//配置服务器端口号,当创建服务器不配置端口号时,默认9501;
define('TCP_SERV_PORT',9501);
//配置服务器模式,默认为进程模式;
define('TCP_SERV_MODEL',SWOOLE_PROCESS);
//配置服务器后台作为守护进程运行,1为守护运行,0为非守护运行;
define('TCP_SERV_DAEMONIZE',1);
//配置服务器worker进程数,建议进程数为cpu核数1-4倍;
define('TCP_SERV_WORKER_NUM',10);
//配置服务器worker进程的最大任务数,一个进程处理完此数值任务将自动释放所有内存和资源，防止PHP进程内存溢出;
define('TCP_SERV_MAX_REQUEST',3000);
//配置服务器是否开启task异步任务,1为开启,0为关闭;
define('TCP_SERV_TASK',0);
//配置服务器task异步进程数,建议进程数不超过cpu核数的500倍;
define('TCP_SERV_TASK_NUM',100);

/**
 * 配置TCP客户端
 */
//配置访问服务器IP,默认127.0.0.1;
define('TCP_CLI_IP','127.0.0.1');
//配置访问服务器端口号,当创建客户端不配置端口号时,默认9501;
define('TCP_CLI_PORT',9501);
//配置客户端最长连接时间,以秒为单位;
define('TCP_TIME_OUT',10);

/**
 * 配置UDP服务器
 */
//配置服务器开关,1为开启,0为关闭,关闭后服务器无法启动;
define('UDP_SERV_ON_OFF',1);
//配置服务器监听IP,默认0.0.0.0,同时监听多个IP;
define('UDP_SERV_IP','0.0.0.0');
//配置服务器端口号,当创建服务器不配置端口号时,默认9502;
define('UDP_SERV_PORT',9502);
//配置服务器模式,默认为进程模式;
define('UDP_SERV_MODEL',SWOOLE_PROCESS);
//配置服务器后台作为守护进程运行,1为守护运行,0为非守护运行;
define('UDP_SERV_DAEMONIZE',1);
//配置服务器worker进程数,建议进程数为cpu核数1-4倍;
define('UDP_SERV_WORKER_NUM',10);
//配置服务器worker进程的最大任务数,一个进程处理完此数值任务将自动释放所有内存和资源，防止PHP进程内存溢出;
define('UDP_SERV_MAX_REQUEST',3000);
//配置服务器是否开启task异步任务,1为开启,0为关闭;
define('UDP_SERV_TASK',0);
//配置服务器task异步进程数,建议进程数不超过cpu核数的500倍;
define('UDP_SERV_TASK_NUM',100);

/**
 * 配置UDP客户端
 */
//配置访问服务器IP,仅支持内网交互,默认127.0.0.1;
define('UDP_CLI_IP','127.0.0.1');
//配置访问服务器端口号,当创建客户端不配置端口号时,默认9502;
define('UDP_CLI_PORT',9502);

/**
 * 配置HTTP服务器
 */
//配置服务器开关,1为开启,0为关闭,关闭后服务器无法启动;
define('HTTP_SERV_ON_OFF',1);
//配置服务器监听IP,默认0.0.0.0,同时监听多个IP;
define('HTTP_SERV_IP','0.0.0.0');
//配置服务器端口号,当创建服务器不配置端口号时,默认9503;
define('HTTP_SERV_PORT',9503);
//配置服务器后台作为守护进程运行,1为守护运行,0为非守护运行;
define('HTTP_SERV_DAEMONIZE',1);
//配置服务器worker进程数,建议进程数为cpu核数1-4倍;
define('HTTP_SERV_WORKER_NUM',10);
//配置服务器worker进程的最大任务数,一个进程处理完此数值任务将自动释放所有内存和资源，防止PHP进程内存溢出;
define('HTTP_SERV_MAX_REQUEST',3000);
//配置服务器是否开启task异步任务,1为开启,0为关闭;
define('HTTP_SERV_TASK',0);
//配置服务器task异步进程数,建议进程数不超过cpu核数的500倍;
define('HTTP_SERV_TASK_NUM',100);

/**
 * 配置HTTP客户端,客户端采用curl与服务器进行数据传输;
 */
//curl-http-post请求配置:
//配置访问服务器IP,默认127.0.0.1;
define('HTTP_POST_CLI_IP','127.0.0.1');
//配置访问服务器端口号,当创建客户端不配置端口号时,默认9503;
define('HTTP_POST_CLI_PORT',9503);
//数据回传,1为直接返回,0为直接输出;
define('HTTP_POST_RETURNTRANSFER',1);
//是否验证SSL证书,1为开启验证,0为不验证;
define('HTTP_POST_SSL_VERIFYPEER',0);
//是否检查SSL证书,2为检查当前域名是否与SSL证书域名匹配,0为不检查;
define('HTTP_POST_SSL_VERIFYHOST',0);
//如果开启SSL验证则需配置SSL证书,默认不配置;
define('HTTP_POST_CAINFO','输入SSL证书绝对路径');
//1为开启头信息,0为关闭头信息;
define('HTTP_POST_HEADER',0);
//响应时间设置,以秒为单位;
define('HTTP_POST_TIMEOUT',10);

//curl-http-get请求配置:
//配置访问服务器IP,默认127.0.0.1;
define('HTTP_GET_CLI_IP','127.0.0.1');
//配置访问服务器端口号,当创建客户端不配置端口号时,默认9503;
define('HTTP_GET_CLI_PORT',9503);
//数据回传,1为直接返回,0为直接输出;
define('HTTP_GET_RETURNTRANSFER',1);
//是否验证SSL证书,1为开启验证,0为不验证;
define('HTTP_GET_SSL_VERIFYPEER',0);
//是否检查SSL证书,2为检查当前域名是否与SSL证书域名匹配,0为不检查;
define('HTTP_GET_SSL_VERIFYHOST',0);
//如果开启SSL验证则需配置SSL证书,默认不配置;
define('HTTP_GET_CAINFO','输入SSL证书绝对路径');
//1为开启头信息,0为关闭头信息;
define('HTTP_GET_HEADER',0);
//响应时间设置,以秒为单位;
define('HTTP_GET_TIMEOUT',10);

/**
 * 配置WEBSOCKET服务器
 */
//配置服务器开关,1为开启,0为关闭,关闭后服务器无法启动;
define('WEBSOCKET_SERV_ON_OFF',1);
//配置服务器是否支持SSL,1为开启,0为关闭
define('WEBSOCKET_SSL_ON_OFF',0);
//配置服务器SSL证书目录
define('CERTIFICATE','');
//配置服务器SSL密钥目录
define('PRIVATEKEY','');
//配置服务器监听IP,默认0.0.0.0,同时监听多个IP;
define('WEBSOCKET_SERV_IP','0.0.0.0');
//配置服务器端口号,当创建服务器不配置端口号时,默认9504;
define('WEBSOCKET_SERV_PORT',9504);
//配置服务器后台作为守护进程运行,1为守护运行,0为非守护运行;
define('WEBSOCKET_SERV_DAEMONIZE',0);
//配置服务器worker进程数,建议进程数为cpu核数1-4倍;
define('WEBSOCKET_SERV_WORKER_NUM',10);
//配置服务器worker进程的最大任务数,防止PHP进程内存溢出;
define('WEBSOCKET_SERV_MAX_REQUEST',3000);
//配置连接成功容器（当客户端连接成功时触发）是否开启循环定时执行,1为开启,0为关闭;
define('WEBSOCKET_RESPONSE_TIME_MODEL',0);
//配置连接成功容器循环定时执行时间,以毫秒为单位;
define('WEBSOCKET_RESPONSE_TIME',1000);
//配置服务器连接心跳检测,以秒为单位;
define('WEBSOCKET_HEARTHBEAT_CHECK_INTERVAL',30);
//配置服务器心跳检测最大闲置时间,即客户端周期时间内没有给服务器再次发送消息将关闭连接,默认为心跳检测时间的2倍加2-5秒的网络延迟弥补;
define('WEBSOCKET_HEARTHBEAT_IDLE_TIME',65);
//配置服务器接收单用户发送消息还是群用户发送消息,1为单聊,0为群聊;
define('WEBSOCKET_CHAT_MODEL',1);
//配置服务器是否开启task异步任务,1为开启,0为关闭;
define('WEBSOCKET_SERV_TASK',0);
//配置服务器task异步进程数,建议进程数不超过cpu核数的500倍;
define('WEBSOCKET_SERV_TASK_NUM',100);

/**
 * 配置RPC服务器
 */
//配置服务器开关,1为开启,0为关闭,关闭后服务器无法启动;
define('RPC_SERV_ON_OFF',1);
//配置服务器监听IP,默认0.0.0.0,同时监听多个IP;
define('RPC_SERV_IP','0.0.0.0');
//配置服务器端口号,当创建服务器不配置端口号时,默认9505;
define('RPC_SERV_PORT',9505);
//配置服务器模式,默认为进程模式;
define('RPC_SERV_MODEL',SWOOLE_PROCESS);
//配置服务器后台作为守护进程运行,1为守护运行,0为非守护运行;
define('RPC_SERV_DAEMONIZE',1);
//配置服务器worker进程数,建议进程数为cpu核数1-4倍;
define('RPC_SERV_WORKER_NUM',10);
//配置服务器worker进程的最大任务数,一个进程处理完此数值任务将自动释放所有内存和资源，防止PHP进程内存溢出;
define('RPC_SERV_MAX_REQUEST',3000);
//配置服务器是否开启task异步任务,1为开启,0为关闭;
define('RPC_SERV_TASK',0);
//配置服务器task异步进程数,建议进程数不超过cpu核数的500倍;
define('RPC_SERV_TASK_NUM',100);

/**
 * 配置RPC客户端
 */
//配置访问服务器IP,默认127.0.0.1;
define('RPC_CLI_IP','127.0.0.1');
//配置访问服务器端口号,当创建客户端不配置端口号时,默认9505;
define('RPC_CLI_PORT',9505);
//配置客户端最长连接时间,以秒为单位;
define('RPC_TIME_OUT',10);

/**
 * 配置Process多进程,开启进程池用pipe管道进行进程间通信,不开启进程池用queue消息队列进行进程间通信;
 */
//配置Process进程是否开启队列消息,1为开启,0为不开启;
define('PROCESS_QUEUE',1);
//配置Process进程作为守护进程运行,1为守护运行,0为非守护运行;
define('PROCESS_DAEMONIZE',1);
//配置进程池数量,仅对pool进程池模式下生效,默认进程池数量为30;
define('PROCESS_POOL',30);

/**
 * 配置协程
 */
//配置协程在不创建协程容器时是否开启IO异步非阻塞模式,1为开启,0为不开启;
define('ASYNC_CO',1);