<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<div id="content"></div>
<?php
$url = API_URL."redis_test";
echo "<input id=\"url\" type=\"hidden\" value=$url>";
?>
<script type="application/javascript">
    var url = document.getElementById('url').value;;
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.open('get',url,false);
    // xmlhttp.setRequestHeader('Authorization','init');//定义头信息入参参数
    xmlhttp.send(null);
    var ob_result = xmlhttp.responseText;
    if(ob_result==''){
        document.getElementById('content').innerText = '请在配置文件config.php中开启加载redis连接类库 或 检查是否需要AUTH验证 或 检查是否安装redis扩展并开启redis服务！';
    }else{
        var ob_result = JSON.parse(ob_result);
        document.getElementById('content').innerText = ob_result.data;
    }
</script>
</body>
</html>