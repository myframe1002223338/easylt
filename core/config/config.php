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

//配置是否加载redis连接类库,1为加载,0为不加载(默认不加载,加载时如果未开启redis-server会报错);
define('REDIS_INCLUDE',0);
//配置redis连接地址,默认127.0.0.1
define('REDIS_HOST','127.0.0.1');
//配置redis端口号,默认6379;
define('REDIS_PORT',6379);
//配置redis连接密码,默认为空;
define('REDIS_AUTH','');
//配置redis数据库名称,默认0;
define('REDIS_DBNAME','0');

/**
 * 访问地址配置,默认为Apache配置,如果仍然用Apache配置并采用Nginx等服务器,请根据框架根目录下的.htaccess自行实现nginx.htaccess的url重写;
 */
//服务器公网IP或域名配置,请以http://或https://开头;
define('ADDRESS','http://127.0.0.1');
//API接口地址,API_URL为默认项，仅适用于Apache;采用Nginx或其他服务器请改为NGINX_API_URL;
define('API_URL',ADDRESS.'/m/v/p/index/');
define('NGINX_API_URL',ADDRESS.'/application/presenter/controller/index.php/');
//入口文件地址,VIEW_PUBLIC为默认项,仅适用于Apache;采用Nginx或其他服务器请改为NGINX_VIEW_PUBLIC;
define('VIEW_PUBLIC',ADDRESS);
define('NGINX_VIEW_PUBLIC',ADDRESS.'/public');

/**
 * curl数据传输配置
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
 * 工具配置
 */
//路径分隔符转换
define('D',DIRECTORY_SEPARATOR);
//error_reporting错误报告开关,0为不报错,-1为显示所有错误,2为显示除了E_NOTICE的所有错误;
define('ERROR_STATE',0);