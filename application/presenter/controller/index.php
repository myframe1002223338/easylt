<?php
include('controller.ini');
class Index{
    public function __construct(){
        global $response;
        Response::data($response);
    }
}
new Index;




