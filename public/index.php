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

if(ROUTE_RUN==1){
    //调用route方法运行路由重定向及重置目录名
    include($dir.D.'core'.D.'route.php');
    core\route_rewrite();
    core\mvp_dir_rewrite($dir);
    core\model_dir_rewrite($dir);
    core\presenter_dir_rewrite($dir);
    exit('路由配置成功,请在config.route.php配置文件中关闭该配置!<br /><br />当前API路由地址示例(Apache服务器):'.API_URL.'model/param/key=value<br /><br />当前API路由地址示例(Nginx等服务器):'.NGINX_API_URL.'model/param/?key=value');
}
//实例化启动页
new Start;

