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
$url = API_URL."mysql_test/a/?name=liteng";
echo "<input id=\"url\" type=\"hidden\" value=$url>";
?>
<script type="application/javascript">
    var data = JSON.stringify({username:'php框架'});
    var url = document.getElementById('url').value;
    var xmlhttp = new XMLHttpRequest();
        xmlhttp.open('post',url,false);
        // xmlhttp.setRequestHeader('Authorization','init');//定义头信息入参参数
        xmlhttp.send(data);
    var ob_result = xmlhttp.responseText;
    var ob_result = JSON.parse(ob_result);
    document.getElementById('content').innerText = ob_result.data;
</script>
</body>
</html>
