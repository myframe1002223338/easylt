<?php
namespace core\lib\UpLoad;
class UpLoad{
public function push_file_resource($file,$path){
    $filecount = count($file['name']);
    for($i=0;$i<$filecount;$i++){
        if(is_uploaded_file($file['tmp_name'][$i])){
            $name = $file['name'][$i];
            $type = $file['type'][$i];
            $tmpname = $file['tmp_name'][$i];
            $error = $file['error'][$i];
            $size = $file['size'][$i];
            switch($type){
                case 'image/pjpeg':$okType = true;
                    break;
                case 'image/jpeg':$okType = true;
                    break;
                case 'image/gif':$okType = true;
                    break;
                case 'image/png':$okType = true;
                    break;
                default:$okType = false;
            }
            if($error==0 && $size<4194304 && $okType && $filecount>0 && $filecount<10){
                $name_string = substr(str_shuffle('abcdefghijklmnopqrstuvwxvz123456789'),0,6);
                $name_string2 = substr(str_shuffle('abcdefghijklmnopqrstuvwxvz123456789'),0,3);
                $filename = $path.$name.mt_rand(1,9999).$name_string2.mt_rand(19,1999).$name_string.'.'.ltrim($type,'image/');
                move_uploaded_file($tmpname,$filename);
                $arr[] = $filename;
            }
        }
    }
    return $arr;
}
}
