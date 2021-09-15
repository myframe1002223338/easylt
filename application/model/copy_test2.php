<?php
$username = $request['username'];
class Select_work{
    public $work;
    public function __construct(){
        global $mysql_orm,$username;
        $this->work = $mysql_orm->model('select')->from('work,copy')->where('username=&$'.$username.'&')->query();
    }
}