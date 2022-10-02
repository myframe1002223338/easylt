<?php
class BaseApi{
    //API数据回传运行基类
    public static function api_run($ob_inter,$response){
        $ob_inter->state($response[0],$response[1],$response[2]);
    }
}