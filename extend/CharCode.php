<?php
//图形验证码封装,前端直接访问该类库文件即可渲染图形验证码;验证码字符串会保留在session中与前端提交的验证码进行比对;
class CharCode{
    public function __construct($width = 100, $height = 50, $num = 4, $type = 'png'){
        session_start();
        $str = 'ABCDEFGHIJKLMNOPURSQUVWXYZabcdefghijklmnopqrstuvwxyz1234567890';
        $str = str_shuffle($str);
        $string = substr($str,0,4);
        $_SESSION['string'] = $string;
        $img = imagecreate($width, $height);
        //浅色的背景
        $randbg = imagecolorallocate($img, mt_rand(130, 255), mt_rand(130, 255), mt_rand(130, 255));
        //深色的字或者点这些干 扰元素
        $randpix = imagecolorallocate($img, mt_rand(0, 120), mt_rand(0, 120), mt_rand(0, 120));
        //背景颜色
        imagefilledrectangle($img, 0, 0, $width, $height, $randbg);
        //画干扰元素
        for($i = 0; $i < 30; $i++){
            imagesetpixel($img, mt_rand(0, $width), mt_rand(0, $height), $randpix);
        }
        //写字
        for($i = 0; $i < $num; $i++){
            $x = floor($width / $num) * $i + 5;
            $y = mt_rand(0, $height-15);
            imagechar($img, 5, $x, $y,  $string[$i], $randpix);
        }
        $header = 'Content-type:image/' . $type;
        ob_clean();
        header($header);
        imagepng($img);
        imagedestroy($img);
        //如前后端代码分离且不在一个服务器,请在这里把session存放到redis中,前端读取redis中的code值(这里的redis连接请自行创建);
    }
    public function __destruct(){
        session_destroy($_SESSION['string']);
    }
}
new CharCode;


