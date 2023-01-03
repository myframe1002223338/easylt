<?php
class Start{
    public function __construct(){
        echo
'<!DOCTYPE HTML>

<html>

<head>

    <link rel="icon" href="/public/assets/icon.png">

    <title>EASYLT-专注多场景高性能开发</title>

    <meta http-equiv="content-type" content="text/html; charset=utf-8" />

    <meta name="keywords" content="EASYLT，EasyLite，PHP框架，MVC，MVP，MSVP，SWOOLE，RabbitMQ，php内存常驻，常驻内存，多进程，协程，新手包，swoole新手包，rabbitmq新手包" />

    <meta name="description" content="一款MSVP架构PHP框架" />

<!--[if lte IE 8]><script src="css/ie/html5shiv.js"></script><![endif]-->

<script src="js/jquery.min.js"></script>

<script src="js/jquery.dropotron.min.js"></script>

<script src="js/jquery.scrollgress.min.js"></script>

<script src="js/skel.min.js"></script>

<script src="js/skel-layers.min.js"></script>

<script src="js/init.js"></script>

<noscript>

    <link rel="stylesheet" href="css/skel.css" />

    <link rel="stylesheet" href="css/style.css" />

    <link rel="stylesheet" href="css/style-wide.css" />

</noscript>

<!--[if lte IE 8]><link rel="stylesheet" href="css/ie/v8.css" /><![endif]-->

</head>

<body class="landing">

<!-- Header -->

<header id="header" class="alt">

    <h1 style="font-size:20px;font-weight:normal;margin-bottom:12px;color:#99857d;position:absolute;top:0px">

            EASYLT

        <span style="color:#99857d;font-size:40px;font-family:Sans-serif;position:absolute;top:0px;left:75px">

            3

        </span>

    </h1>

    <nav id="nav">

        <ul>

            <li><a href="'.VIEW_PUBLIC.'version">历史版本</a></li>

        <li>

<a href="" class="icon fa-angle-down">技术支持</a>

<ul>

<li><a href="'.VIEW_PUBLIC.'document3-1" target="_blank">&nbsp&nbsp&nbsp&nbsp&nbspEASYLT文档</a></li>

<li>

<a href="" class="icon fa-angle-left">&nbsp&nbsp&nbsp&nbsp新手包系列</a>

<ul>

<li><a href="'.VIEW_PUBLIC.'swoole-novice">Swoole 新手包</a></li>

<li><a href="">即将到来...</a></li>

</ul>

</li>

</ul>

</li>

            <!--<li><a href="#" class="button">Sign Up</a></li>-->

        </ul>

    </nav>

</header>

<!-- Banner -->

<section id="banner">

    <image src="/public/assets/icon.png" style="width:110px;margin-top:-40px"></image><br /><br />

    <b style="color:#ffffff;font-size:50px">EasyLite</b><br /><br /><br />

    <p>欢迎使用EASYLT 3&nbsp&nbsp&nbsp常驻内存型PHP框架</p>

    <ul class="actions">

        <li><a href="'.VIEW_PUBLIC.'download_frame" class="button special">下载框架</a></li>

        <li><a href="'.VIEW_PUBLIC.'download_explain" class="button">下载手册</a></li>

    </ul>

</section>

<!-- Main -->

<section id="main" class="container">

    <section class="box special">

        <header class="major">

            <h2 style="color:#333333;font-size:25px"><b>关于框架</b></h2>';

        include_once('..'.D.'core'.D.'lib'.D.'IsMobile.php');
        $ob = new \core\lib\IsMobile\IsMobile;
        $ismobile_result = $ob->get_ismobile();
        if($ismobile_result==true){
            echo '<p>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspEASYLT全名EasyLite，寓意为简单、轻松、优雅的开发、维护项目。</p>';
        }else{
            echo '<p style="color:#333333;font-size:17px">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspEASYLT全名EasyLite，寓意为简单、轻松、优雅的开发、维护项目。框架采用全新的MSVP架构，<br /><br />&nbspMSVP是MVC/MVP架构的升级版，在原有的model层中增加server层，可实现业务代码内存常驻，用<br /><br />于多进程、协程的TCP、UDP、HTTP、WebSocket通信协议服务及RPC服务搭建，可快速实现业务<br /><br />RESTful / RPC - API，适用于后端各种开发应用场景。&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</br></p>';
        }
        echo ' </header>

    </section>

    <section class="box special features">

        <div class="features-row">

            <section>

                <span class="icon major fa-bolt accent2"></span>

                <h3>高效率</h3>

                <p>从0-1快速实现业务&nbsp&nbsp性能优化堪比原生</p>

            </section>

            <section>

                <span class="icon major fa-area-chart accent3"></span>

                <h3>低成本</h3>

                <p>低代码模块化设计&nbsp&nbsp60分钟内掌握开发</p>

            </section>

        </div>

        <div class="features-row">

            <section>

                <span class="icon major fa-cloud accent4"></span>

                <h3>多元化</h3>

                <p>FPM/常驻内存运行&nbsp&nbsp多场景开发类库支持</p>

            </section>

            <section>

                <span class="icon major fa-lock accent5"></span>

                <h3>易维护</h3>

                <p>MODEL模型解耦&nbsp&nbspLOGIC逻辑承载业务</p>

            </section>

        </div>

    </section>

</section>

<!-- Footer -->

<footer id="footer" style="background:#444444">

    <ul class="icons" style="color:#FFFFFF">

        <li><a href="'.ADDRESS.'" class="icon fa-home"><span class="label">home</span></a></li>

        <li><a href="https://github.com/myframe1002223338/easylt/tree/master" class="icon fa-github"><span class="label">github</span></a></li>

    </ul>

    <ul class="copyright" style="color:#FFFFFF">

        <li>工作室邮箱 1002223338@qq.com</li><li>&copy; 李腾. All rights reserved.</li><li><a href="https://beian.miit.gov.cn">陕ICP备20008518号-3</a></li>

    </ul>

</footer>

</body>

</html>';
        //echo '<head><link rel="icon" href="/public/assets/icon.png"><title>EASYLT</title><style type="text/css">*{padding:0;margin:0;}a{text-decoration:none;color:grey}h1{font-size:100px;font-weight:normal;margin-bottom:12px;color:#6bc5a4}p{line-height:1.6em;font-size:42px}</style></head><body><div style="padding:24px 48px;"><h1>EASYLT&nbsp<span style="color:#c9d1d6;font-size:125px;font-family:Sans-serif">3</span></h1><p><span style="font-size:25px;color:#333333">&nbsp欢迎使用EASYLT v3.1&nbsp&nbsp&nbsp初次使用请仔细阅读</span><a href="http://www.easylt.cn/?href=download_explain" style="font-size:23px;">【EASYLT框架手册】</a></div><div style="position:absolute;top:90%;left:0;right:0;text-align:center;color:grey"><a href="http://www.easylt.cn">EASYLT官网</a> | Copyright © liteng</div></body>';
    }
}
new Start;





