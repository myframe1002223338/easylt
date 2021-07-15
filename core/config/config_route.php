<?php
/**
 * 配置route.php路由配置中的$_SERVER['REQUEST_URI']参数在不同运行环境下的数组下标
 */
//获取URL中model文件名用于路由分发
define('OS_MODEL',5);
//获取URL中的参数用于判断model文件下多接口业务分发对接
define('OS_PARAM',6);