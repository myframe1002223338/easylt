<?php
$username = $request['username'];
class Select_sex{
    public $sex;
    public function __construct(){
        global $mysql_orm,$username;
        $this->sex = $mysql_orm->model('select')->from('sex,copy')->where('username=&$'.$username.'&')->query();
    }
}




