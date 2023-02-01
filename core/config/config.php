<?php
/**
 * 数据库连接配置
 */
//配置mysql连接用户名
define('DB_USER','root');
//配置mysql连接密码
define('DB_PWD','root');
//配置mysql连接地址,默认127.0.0.1
define('DB_HOST','127.0.0.1');
//配置mysql字符集
define('DB_CHARSET','utf8mb4');
//配置mysql数据库名称
define('DB_NAME','easylt_test');

//配置是否使用redis,1为使用,0为不使用(默认不使用,使用时如果未开启redis-server会异常导致程序无法执行);
define('REDIS_INCLUDE',0);
//配置redis连接地址,默认127.0.0.1
define('REDIS_HOST','127.0.0.1');
//配置redis端口号,默认6379;
define('REDIS_PORT',6379);
//配置redis连接密码,默认为空;
define('REDIS_AUTH','');
//配置redis数据库名称,默认0;
define('REDIS_DBNAME',0);

/**
 * 访问地址配置
 */
//加载路由配置文件
include('config_route.php');

//服务器公网IP或域名配置,请以http://或https://开头;
define('ADDRESS','http://127.0.0.1');

//Index通用控制器下的API接口地址,Apache/Nginx服务器请支持.htaccess/nginx.htaccess,否则请用以下API_URL_OTHER;
define('API_URL',ADDRESS.'/'.API_URL_ROUTE[0].'/'.API_URL_ROUTE[1].'/'.API_URL_ROUTE[2].'/Index/');
APPLICATION_RENAME[2] == 'presenter' ? define('PRESENTER',APPLICATION_RENAME[2]) : define('PRESENTER',APPLICATION_RENAME[2].'_presenter');
APPLICATION_RENAME[3] == 'controller' ? define('CONTROLLER',APPLICATION_RENAME[3]) : define('CONTROLLER',APPLICATION_RENAME[3].'_controller');
define('API_URL_OTHER',ADDRESS.'/application/'.PRESENTER.'/'.CONTROLLER.'/Index.php/');

//入口加载文件地址,Apache/Nginx服务器请支持.htaccess/nginx.htaccess,否则请用以下VIEW_PUBLIC_OTHER;
define('VIEW_PUBLIC',ADDRESS.'/?href=');
define('VIEW_PUBLIC_OTHER',ADDRESS.'/public/?href=');

/**
 * cURL数据传输配置
 */
//curl-post请求配置:
//数据回传,1为直接返回,0为直接输出;
define('POST_RETURNTRANSFER',1);
//是否验证SSL证书,1为开启验证,0为不验证;
define('POST_SSL_VERIFYPEER',0);
//是否检查SSL证书,2为检查当前域名是否与SSL证书域名匹配,0为不检查;
define('POST_SSL_VERIFYHOST',0);
//如果开启SSL验证则需配置SSL证书,默认不配置;
define('POST_CAINFO','输入SSL证书绝对路径');
//1为开启头信息,0为关闭头信息;
define('POST_HEADER',0);
//响应时间设置,以秒为单位;
define('POST_TIMEOUT',10);

//curl-get请求配置:
//数据回传,1为直接返回,0为直接输出;
define('GET_RETURNTRANSFER',1);
//是否验证SSL证书,1为开启验证,0为不验证;
define('GET_SSL_VERIFYPEER',0);
//是否检查SSL证书,2为检查当前域名是否与SSL证书域名匹配,0为不检查;
define('GET_SSL_VERIFYHOST',0);
//如果开启SSL验证则需配置SSL证书,默认不配置;
define('GET_CAINFO','输入SSL证书绝对路径');
//1为开启头信息,0为关闭头信息;
define('GET_HEADER',0);
//响应时间设置,以秒为单位;
define('GET_TIMEOUT',10);

/**
 * 其他配置
 */
//路径分隔符转换
define('D',DIRECTORY_SEPARATOR);
//error_reporting错误报告开关,0为不报错,-1为显示所有错误,2为显示除了E_NOTICE的所有错误;
define('ERROR_STATE',0);
//时区配置
define('TIMEZONE','PRC');

/**
 * 路径配置
 */
define('ROOT_PATH',dirname(dirname(__DIR__)).D);//框架根目录路径
define('APP_PATH',ROOT_PATH.'application'.D);//application目录路径
define('EXTEND_PATH',ROOT_PATH.'extend'.D);//extend目录路径
define('CORE_PATH',ROOT_PATH.'core'.D);//core目录路径
define('ASSETS_PATH',ROOT_PATH.'public'.D.'assets'.D);//view视图静态资源路径
define('LIB_PATH',ROOT_PATH.'core'.D.'lib'.D);// lib类库目录路径
define('ERRORS_PATH',ROOT_PATH.'core'.D.'log'.D);//log日志目录路径



