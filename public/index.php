<?php
// +----------------------------------------------------------------------
// | EASYLT 3
// +----------------------------------------------------------------------
// | Copyright (c) 2021 liteng All rights reserved.
// +----------------------------------------------------------------------
// | Author: liteng <1002223338@qq.com>
// +----------------------------------------------------------------------

// [ 应用入口文件 ]
$dir = dirname(__DIR__);
include($dir.DIRECTORY_SEPARATOR.'core'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'config.php');
include($dir.D.'core'.D.'base.php');
AutoLoad::view_load($dir);



