<?php
namespace core\lib\DownLoad;
//下载类库
class DownLoad{
    public function push_filepath($filepath){
        header('ontent-type:application/octet-stream');
        header('accept-ranges:bytes');
        header('accept-length:'.filesize($filepath));
        header('content-disposition:attachment;filename='.$filepath);
        readfile($filepath);
    }
}