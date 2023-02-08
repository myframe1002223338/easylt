<?php
/**
 * 头信息配置
 * !如果无法读取Authorization值且服务器为Apache,请保证php版本>=7.3并在在.htaccess中配置以下即可恢复正常:
 * SetEnvIf Authorization .+ HTTP_AUTHORIZATION=$0
 */
//配置在Index主控制器中是否验证头信息,1为验证,0为不验证(默认不验证)
define('AUTH_ON_OFF',0);
//头信息Authorization配置,初始化值为init,生产环境下请自行更改;
define('AUTH','init');

/**
 * API跨域配置
 */
//配置单域名(或*不限制)、多域名开关,1为单域名,0为多域名;
define('URL_ON_OFF',1);
//单域名(或*不限制)跨域,默认为空拒绝跨域访问;
define('SINGLE_URL','');
//多域名跨域,以下数组元素为示例;
define('ALL_URL',['https://www.baidu.com','https://www.tmall.com']);







