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

    <meta name="keywords" content="EASYLT，EasyLite，PHP框架，MVC，MVP，MSVP，SWOOLE，RabbitMQ，php内存常驻" />

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

<!--            <li>-->
<!---->
<!--                <a href="" class="icon fa-angle-down">文档支持</a>-->
<!---->
<!--                <ul>-->
<!---->
<!--                    <li><a href="">框架部署</a></li>-->
<!---->
<!--                    <li><a href="">使用框架</a></li>-->
<!---->
<!--                    <li>-->
<!---->
<!--                        <a href="">进阶开发</a>-->
<!---->
<!--                        <ul>-->
<!---->
<!--                            <li><a href="">Swoole</a></li>-->
<!---->
<!--                            <li><a href="">RabbitMQ</a></li>-->
<!---->
<!--                        </ul>-->
<!---->
<!--                    </li>-->
<!---->
<!--                </ul>-->
<!---->
<!--            </li>-->

            <!--<li><a href="#" class="button">Sign Up</a></li>-->

        </ul>

    </nav>

</header>

<!-- Banner -->

<section id="banner">

    <image src="/public/assets/icon.png" style="width:110px;margin-top:-40px"></image><br /><br />

    <b style="color:#ffffff;font-size:50px">EasyLite</b><br /><br /><br />

    <p>欢迎使用EASYLT v3.0&nbsp&nbsp&nbsp使用前请仔细阅读手册</p>

    <ul class="actions">

        <li><a href="'.VIEW_PUBLIC.'download_frame" class="button special">下载框架</a></li>

        <li><a href="'.VIEW_PUBLIC.'download_explain" class="button">下载手册</a></li>

    </ul>

</section>

<!-- Main -->

<section id="main" class="container">

    <section class="box special">

        <header class="major">

            <h2 style="color:#333333;font-size:25px"><b>关于框架</b></h2>

            <p style="color:#333333;font-size:17px">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspEASYLT框架采用全新的MSVP架构，MSVP是MVC/MVP架构的升级版，在原有的model层中<br /><br />&nbsp&nbsp增加server层，可实现业务代码内存常驻，用于多进程、协程的TCP、UDP、HTTP、WEBSOCKET<br /><br />通信协议服务及RPC服务搭建，可快速实现业务RESTful、RPC的API及覆盖web、小程序、APP等<br /><br />&nbsp&nbsp各种开发应用场景。&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</b></p>

        </header>

<!--        <span class="image featured"><img src="images/pic01.jpg" alt=""/></span>-->

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

                <p>FPM/内存常驻开发&nbsp&nbsp多场景开发类库支持</p>

            </section>

            <section>

                <span class="icon major fa-lock accent5"></span>

                <h3>易维护</h3>

                <p>VIEW/MODEL解耦&nbsp&nbsp全新MSVP架构设计</p>

            </section>

        </div>

    </section>

<!--    <div class="row">-->
<!---->
<!--        <div class="6u 12u(2)">-->
<!---->
<!--            <section class="box special">-->
<!---->
<!--                <span class="image featured"><img src="images/pic02.jpg" alt="" /></span>-->
<!---->
<!--                <h3>Sed lorem adipiscing</h3>-->
<!---->
<!--                <p>Integer volutpat ante et accumsan commophasellus sed aliquam feugiat lorem aliquet ut enim rutrum phasellus iaculis accumsan dolore magna aliquam veroeros.</p>-->
<!---->
<!--                <ul class="actions">-->
<!---->
<!--                    <li><a href="#" class="button alt">Learn More</a></li>-->
<!---->
<!--                </ul>-->
<!---->
<!--            </section>-->
<!---->
<!--        </div>-->
<!---->
<!--        <div class="6u 12u(2)">-->
<!---->
<!--            <section class="box special">-->
<!---->
<!--                <span class="image featured"><img src="images/pic03.jpg" alt="" /></span>-->
<!---->
<!--                <h3>Accumsan integer</h3>-->
<!---->
<!--                <p>Integer volutpat ante et accumsan commophasellus sed aliquam feugiat lorem aliquet ut enim rutrum phasellus iaculis accumsan dolore magna aliquam veroeros.</p>-->
<!---->
<!--                <ul class="actions">-->
<!---->
<!--                    <li><a href="#" class="button alt">Learn More</a></li>-->
<!---->
<!--                </ul>-->
<!---->
<!--            </section>-->
<!---->
<!--        </div>-->
<!---->
<!--    </div>-->

</section>

<!-- CTA -->

<!--<section id="cta">-->
<!---->
<!--    <h2>Sign up for beta access</h2>-->
<!---->
<!--    <p>Blandit varius ut praesent nascetur eu penatibus nisi risus faucibus nunc.</p>-->
<!---->
<!--    <form>-->
<!---->
<!--        <div class="row uniform 50%">-->
<!---->
<!--            <div class="8u 12u(3)">-->
<!---->
<!--                <input type="email" name="email" id="email" placeholder="Email Address" />-->
<!---->
<!--            </div>-->
<!---->
<!--            <div class="4u 12u(3)">-->
<!---->
<!--                <input type="submit" value="Sign Up" class="fit" />-->
<!---->
<!--            </div>-->
<!---->
<!--        </div>-->
<!---->
<!--    </form>-->
<!---->
<!--</section>-->

<!-- Footer -->

<footer id="footer" style="background:#444444">

    <ul class="icons" style="color:#FFFFFF">

        <li><a href="'.ADDRESS.'" class="icon fa-home"><span class="label">home</span></a></li>

        <li><a href="https://github.com/myframe1002223338/easylt/tree/master" class="icon fa-github"><span class="label">github</span></a></li>

    </ul>

    <ul class="copyright" style="color:#FFFFFF">

        <li>作者邮箱 1002223338@qq.com</li><li>&copy; 李腾. All rights reserved.</li><li><a href="https://beian.miit.gov.cn">陕ICP备20008518号-3</a></li>

    </ul>

</footer>

</body>

</html>';
        //echo '<head><link rel="icon" href="/public/assets/icon.png"><title>EASYLT</title><style type="text/css">*{padding:0;margin:0;}a{text-decoration:none;color:grey}h1{font-size:100px;font-weight:normal;margin-bottom:12px;color:#6bc5a4}p{line-height:1.6em;font-size:42px}</style></head><body><div style="padding:24px 48px;"><h1>EASYLT&nbsp<span style="color:#c9d1d6;font-size:125px;font-family:Sans-serif">3</span></h1><p><span style="font-size:25px;color:#333333">&nbsp欢迎使用EASYLT v3.0&nbsp&nbsp&nbsp初次使用请仔细阅读</span><a href="http://www.easylt.cn/href/download_explain" style="font-size:23px;">【EASYLT框架手册】</a></div><div style="position:absolute;top:90%;left:0;right:0;text-align:center;color:grey"><a href="http://www.easylt.cn">EASYLT官网</a> | Copyright © liteng</div></body>';
    }
}






