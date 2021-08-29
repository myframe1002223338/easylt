<?php
$redis->set('name','redis连接成功！');
$data = $redis->get('name');
$redis->close();


