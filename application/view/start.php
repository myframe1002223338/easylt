<?php
class Start{
    public function __construct(){
		//官网版 含框架下载链接
        echo '<head><title>EASYLT V2.2</title><meta name="keywords" content="EASYLT，EasyLite，PHP框架，MVC，MVP"><meta name="description" content="一款全新MSVP架构PHP框架"><style type="text/css">*{ padding: 0; margin: 0; } a{text-decoration: none;color: grey} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; color: 6bc5a4} p{ line-height: 1.6em; font-size: 42px }</style></head><body><div style="padding: 24px 48px;"><h1>EASYLT</h1><p><span style="font-size: 28px; color: 333333">欢迎使用EASYLT v2.2&nbsp&nbsp一款MSVP多场景PHP框架</span>&nbsp<a title="linux下载命令：wget http://easylt.ilivecode.com/easylt2.2.zip" href="'.VIEW_PUBLIC.'/href/download_frame" style="font-size:25px;">【下载EASYLT框架】</a><a href="'.VIEW_PUBLIC.'/href/download_explain" style="font-size:25px;">【下载EASYLT手册】</a></p><br /><span style="font-size:20px;"><a href="' .VIEW_PUBLIC.'/href/view_mysql_test">点击进入view视图mysql测试页面</a><br /><br /><a href="' .VIEW_PUBLIC.'/href/view_redis_test">点击进入view视图&nbspredis&nbsp测试页面</a><br /><br /><a href="' .VIEW_PUBLIC.'/href/websocket_test">点击进入websocket连接测试页面</a><br /><br /><a href="' .VIEW_PUBLIC.'/href/rpc_test">点击开始&nbsp&nbspRPC&nbsp&nbsp远程过程调用测试</a></span></div><div style="position:absolute;top:90%;left:0;right:0;text-align:center;color:grey"><a href="https://beian.miit.gov.cn">陕ICP备20008518号-2</a> | Copyright © 李腾</div></body>';
		
		//开发版 不含框架下载链接
		//echo '<style type="text/css">*{ padding: 0; margin: 0; } a{text-decoration: none;color: grey} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"><h1>EASYLT</h1><p><span style="font-size:28px">欢迎使用EASYLT v2.2&nbsp&nbsp一款MSVP多场景PHP框架</span></p><br /><span style="font-size:20px;"><a href="' .VIEW_PUBLIC.'/href/view_mysql_test">点击进入view视图mysql测试页面</a><br /><br /><a href="' .VIEW_PUBLIC.'/href/view_redis_test">点击进入view视图&nbspredis&nbsp测试页面</a><br /><br /><a href="' .VIEW_PUBLIC.'/href/websocket_test">点击进入websocket连接测试页面</a><br /><br /><a href="' .VIEW_PUBLIC.'/href/rpc_test">点击开始&nbsp&nbspRPC&nbsp&nbsp远程过程调用测试</a></span></div>';
    }
}






