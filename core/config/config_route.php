<?php
/**
 * 配置路由$_SERVER['REQUEST_URI']参数在不同运行环境下的数组下标,默认不配置;
 */
//配置API-URL中controller控制器文件名数组下标
define('OS_CONTROLLER',4);
//配置API-URL中model模型文件名数组下标
define('OS_MODEL',5);
//配置API-URL中param参数数组下标
define('OS_PARAM',6);
//配置API-URL中get参数数组下标
define('OS_GET',7);

/**
 * 路由重写：配置API路由地址及重置目录名,开发环境可不配置;生产环境建议重新配置保证安全性;
 */
//配置是否在入口文件中运行以下参数配置,成功运行一次即可手动关闭该配置,1为运行,0为不运行;配置好以下参数配置后需访问入口文件方可生效,如访问http://127.0.0.1
define('ROUTE_RUN',0);
//配置API路由地址重写,默认为http://127.0.0.1/m/v/p,请更改以下参数重新配置API路由地址;
define('API_URL_ROUTE',[
    'm',//application目录名路由重写
    'v',//presenter目录名路由重写
    'p'//controller目录名路由重写
]);
//重置application目录下全部目录名
define('APPLICATION_RENAME', [
    'model',//model目录名配置
    'server',//server目录名配置
    'presenter',//presenter目录名配置
    'controller',//controller目录名配置
    'logic',//logic目录名配置
    'view'//view目录名配置
]);



