<?php
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
 * 头信息配置
 */
//配置在controller控制器中是否验证头信息,1为验证,0为不验证(默认不验证)
define('AUTH_ON_OFF',0);
//头信息Authorization配置,初始化值为init,生产环境下请自行更改;
define('AUTH','init');

/**
 * API跨域配置
 */
//配置单域名、多域名开关,1为单域名,0为多域名;
define('URL_ON_OFF',1);
//单域名或所有域名(*)跨域,默认为空拒绝跨域访问;
define('SINGLE_URL','*');
//多域名跨域,以下数组元素为示例;
define('ALL_URL',['https://www.baidu.com','https://www.tmall.com']);




