<?php
include(APP_PATH.'common.php');
//动态加载类库
function autoload_lib(){
    include_once('IsMobile.php');
    include_once('UpLoad.php');
    include_once('DownLoad.php');
    include_once('GetIp.php');
    include_once('CharCode.php');
}
spl_autoload_register('autoload_lib');
use core\lib\IsMobile\IsMobile as ismobile;
use core\lib\UpLoad\UpLoad as upload;
use core\lib\DownLoad\DownLoad as download;
use core\lib\GetIp\GetIp as getip;
use core\lib\CharCode\CharCode as charcode;

//数据打印
function dump($value,$label=null){
    ob_start();
    var_dump($value);
    $output = ob_get_clean();
    $output = preg_replace('/\]\=\>\n(\s+)/m', '] => ', $output);
    if(!extension_loaded('xdebug')){
        $output = htmlspecialchars($output);
    }
    if($label){
        $label .= '：';
    }
    if(is_array($value) || is_object($value)){
        $output = '<pre style="font-size: 18px">'.$label.$output.'</pre>';
    }else{
        $output = $label.$output;
    }
    echo $output;
}
//异常日志记录
function onerror($message,$path=null){
    if($path===null){
        $error = date('y-m-d h:i:s',time()).$message.PHP_EOL;
        error_log($error,3,ERRORS_PATH.'errors.log');
    }else{
        $error = date('y-m-d h:i:s',time()).$message.PHP_EOL;
        error_log($error,3,$path);
    }
}
//判断PC浏览器或移动手机设备访问网站,用于项目适配;调用类方法后,返回值为true则为手机设备访问,为false则为PC浏览器访问;
function ismobile(){
    $ob = new ismobile;
    return $ob->get_ismobile();
}
//上传图片类库
function upload($file,$path){
    $ob = new upload;
    return $ob->push_file_resource($file,$path);
}
//下载类库
function download($filepath){
    $ob = new download;
    return $ob->push_filepath($filepath);
}
//获取访问者IP
function getip(){
    $ob = new getip;
    return $ob->get_client_ip();
}
//图形验证码,前端直接访问该类库文件即可渲染图形验证码;验证码字符串会保留在session中与前端提交的验证码进行比对;
function charcode(){
    return new charcode;
}