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
define('REDIS_DBNAME',0);

/**
 * 访问地址配置,默认为Apache配置,如果仍然用Apache配置并采用Nginx等服务器,请根据框架根目录下的.htaccess自行实现nginx.htaccess的url重写;
 */
//加载路由配置文件
include('config_route.php');
//服务器公网IP或域名配置,请以http://或https://开头;
define('ADDRESS','http://127.0.0.1');
//Index通用控制器下的API接口地址,Apache/Nginx服务器请支持.htaccess/nginx.htaccess,否则请用以下API_URL_OTHER;
define('API_URL',ADDRESS.'/'.API_URL_ROUTE[0].'/'.API_URL_ROUTE[1].'/'.API_URL_ROUTE[2].'/index/');
APPLICATION_RENAME[2] == 'presenter' ? define('PRESENTER',APPLICATION_RENAME[2]) : define('PRESENTER',APPLICATION_RENAME[2].'_presenter');
APPLICATION_RENAME[3] == 'controller' ? define('CONTROLLER',APPLICATION_RENAME[3]) : define('CONTROLLER',APPLICATION_RENAME[3].'_controller');
define('API_URL_OTHER',ADDRESS.'/application/'.APPLICATION_RENAME[2].'/'.APPLICATION_RENAME[3].'/index.php/');
//入口加载文件地址,Apache/Nginx服务器请支持.htaccess/nginx.htaccess,否则请用以下VIEW_PUBLIC_OTHER;
define('VIEW_PUBLIC',ADDRESS.'/?href=');
define('VIEW_PUBLIC_OTHER',ADDRESS.'/public/?href=');
//view视图静态资源路径
define('STATIC','/public/assets/');



/**
 * 工具配置
 */
//路径分隔符转换
define('D',DIRECTORY_SEPARATOR);
//error_reporting错误报告开关,0为不报错,-1为显示所有错误,2为显示除了E_NOTICE的所有错误;
define('ERROR_STATE',0);