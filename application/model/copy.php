<?php
include('copy_test1.php');
include('copy_test2.php');
$select_sex = new Select_sex;
$select_work = new Select_work;
$sex = $select_sex->sex[0]['sex'];
$work = $select_work->work[0]['work'];
class Select_pm_work{
    public $pm_work;
    public function __construct(){
        global $mysql_orm,$work;
        $this->pm_work = $mysql_orm->model('select')->from('work,copy')->where('username=&$'.$work.'&')->query();
    }
}
$select_pm_work = new Select_pm_work;
$pm_work = $select_pm_work->pm_work[0]['work'];
