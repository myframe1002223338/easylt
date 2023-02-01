<?php
// +----------------------------------------------------------------------
// | EASYLT 3.2.7
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
date_default_timezone_set(TIMEZONE);
include(CORE_PATH.'base_view.php');



