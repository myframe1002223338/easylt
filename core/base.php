<?php
class AutoLoad{
    //view视图加载基类
    public static function view_load($dir){
        //打开view视图目录文件
        if(file_exists($dir.D.'application'.D.'view')){
            $view_path = $dir.D.'application'.D.'view';
        }else{
            $view_path = $dir.D.'application'.D.APPLICATION_RENAME[5].'_view';
        }
        $view_fopen = opendir($view_path);
        //遍历view视图目录文件
        while($view_fread = readdir($view_fopen)){
            $view_fread = explode('.php',$view_fread);
            $arr[] = $view_fread;
        }
        function filter($var){
            if($var[0]=='.' || $var[0]=='..'){
                return false;
            }else{
                return true;
            }
        }
        $arr = array_filter($arr,'filter');
        $arr = array_values($arr); 
		//遍历view视图目录文件名数组并处理为一维数组用于判断$href是否存在其中
		$new_arr = [];
		foreach($arr as $key => $value){
			$new_arr[] = $value[0];
		}
        //获取页面href参数
        $href = $_GET['href'];
		if(in_array($href,$new_arr)){
		    if(include_once($dir.D.'application'.D.'view'.D.$href.'.php')){

            }else{
                include_once($dir.D.'application'.D.APPLICATION_RENAME[5].'_view'.D.$href.'.php');
            }
		}else{
		    if(include_once($dir.D.'application'.D.'view'.D.'start.php')){

            }else{
                include_once($dir.D.'application'.D.APPLICATION_RENAME[5].'_view'.D.'start.php');
            }
		}
        closedir($view_fopen);
    }
    //API数据回传运行基类
    public static function api_run($ob_inter,$response){
        $ob_inter->state($response[0],$response[1],$response[2]);
    }
}



