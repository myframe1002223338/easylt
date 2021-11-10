<?php
include('..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'core'.DIRECTORY_SEPARATOR.'controller.php');
class Index{
    public function __construct(){
        global $response;
        Response::data($response);
    }
}






