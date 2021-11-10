# EASYLT 3

#### 介绍
EASYLT全名EasyLite，寓意为简单、轻松、优雅的开发、维护项目。

#### 软件架构
框架采用全新的MSVP架构，MSVP是MVC/MVP架构的升级版，在原有的model层中增加server层，可实现业务代码内存常驻，用于多进程、协程的TCP、UDP、HTTP、WEBSOCKET通信协议服务及RPC服务搭建，可快速实现业务RESTful、RPC的API及覆盖web、小程序、APP等各种开发应用场景。    


#### 下载安装

- [http://www.easylt.cn](http://www.easylt.cn)官网直接下载，可在历史版本中下载老版本。

- Git下载指令：<code>git clone https://github.com/myframe1002223338/easylt.git</code>

- wget下载指令<code>wget http://easylt.cn/easylt.zip</code>

- composer安装指令：composer create-project "easylt3/easylt3":"dev-master"

  PS：composer安装显示以下错误：

  ​        [Symfony\Component\Process\Exception\RuntimeException] 

  ​        The Process class relies on proc_open, which is not available on your PHP installation. 

  ​        解决方法：打开php.ini，搜索disable_functions，找到disable_functions = xxx,xxx,xxx...删除其中的

  ​        proc_open保存并重启服务器即可。

#### 使用文档

[EASYLT 3 开发手册](http://www.easylt.cn/?href=download_explain)

#### 版权信息

EASYLT 3 遵循MIT开源协议发布，并提供免费试用。

本项目包含的第三方源码和二进制文件之版权信息另行标注。 

版权所有Copyright © 2006-2020 by 李腾 ([http://www.easylt.cn](http://www.easylt.cn/)) All rights reserved。

更多细节参阅 LICENSE