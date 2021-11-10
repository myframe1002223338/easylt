# EASYLT 3

#### Description
Easylt's full name is easylite, which means a simple, easy and elegant development and maintenance project.

#### Software Architecture
The framework adopts a new msvp architecture. Msvp is an upgraded version of MVC / MVP architecture. The server layer is added to the original model layer to realize the memory resident of business code. It is used to build TCP, UDP, HTTP, websocket communication protocol services and RPC services for multi process and collaborative processes. It can quickly realize business restful and RPC APIs and cover various development and application scenarios such as web, applet and app.

#### Download and install

1. [http://www.easylt.cn](http://www.easylt.cn)Download directly from the official website, and you can download the old version in the historical version.

2. GIT download：<code>git clone https://github.com/myframe1002223338/easylt.git</code>

3. wget download：<code>wget http://easylt.cn/easylt.zip</code>

4. composer installation instructions：composer create-project "easylt3/easylt3":"dev-master"

   PS：The composer installation displays the following error：

   ​        [Symfony\Component\Process\Exception\RuntimeException] 

   ​        The Process class relies on proc_open, which is not available on your PHP installation. 

   ​        Solution: open php.ini and search for disable_ Functions, find disable_ Functions = XXX, XXX, XXX... 

   ​        Delete proc_ Open save and restart the server.

#### Document

[EASYLT 3 Development Manual](http://www.easylt.cn/?href=download_explain)

#### Copyright Information

Easylt 3 is released in accordance with MIT open source agreement and provides free trial.

The copyright information of the third-party source code and binary files contained in this project will be marked separately.

Copyright © 2006-2020 by Li Teng ([http://www.easylt.cn ]( http://www.easylt.cn/ )) All rights reserved。

See LICENSE for more details



