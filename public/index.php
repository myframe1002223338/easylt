<?php
// +----------------------------------------------------------------------
// | EASYLT
// +----------------------------------------------------------------------
// | Copyright (c) 2021 liteng All rights reserved.
// +----------------------------------------------------------------------
// | Author: liteng <1002223338@qq.com>
// +----------------------------------------------------------------------

// [ 应用入口文件 ]

$dir = dirname(__DIR__);
include($dir.DIRECTORY_SEPARATOR.'core'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'config.php');
error_reporting(ERROR_STATE);
header('Content-type:text/html;charset=utf-8');
date_default_timezone_set('Asia/chongqing');
//加载swoole配置文件
include($dir.D.'core'.D.'config'.D.'config_swoole.php');
//动态加载swoole客户端类库
function autoload(){
    global $dir;
    include($dir.D.'core'.D.'static'.D.'Tcp_client.php');
    include($dir.D.'core'.D.'static'.D.'Udp_client.php');
    include($dir.D.'core'.D.'static'.D.'Http_client.php');
    include($dir.D.'core'.D.'static'.D.'Rpc_client.php');
}
spl_autoload_register('autoload');

//加载view视图运行类库
include($dir.D.'core'.D.'base.php');
AutoLoad::view_load($dir);
//实例化启动页
new Start;
