<?php
/**
 * 头信息配置
 */
//配置在controller控制器中是否验证头信息,1为验证,0为不验证(默认不验证)
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




