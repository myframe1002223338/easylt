<?php
use core\static_formwork\Curl\Curl as Curl;
use core\static_formwork\Curl_get\Curl_get as Curl_get;
class AutoLoad{
    //view视图加载基类
    public static function view_load($dir){
        error_reporting(ERROR_STATE);
        header('Content-type:text/html;charset=utf-8');
        date_default_timezone_set(TIMEZONE);
        //加载swoole配置文件
        @include($dir.D.'core'.D.'config'.D.'config_swoole.php');

        //动态加载类库
        function autoload(){
            global $dir;
            include($dir.D.'core'.D.'static'.D.'Curl.php');
            include($dir.D.'core'.D.'static'.D.'Curl_get.php');
            include($dir.D.'core'.D.'static'.D.'Tcp_client.php');
            include($dir.D.'core'.D.'static'.D.'Udp_client.php');
            include($dir.D.'core'.D.'static'.D.'Http_client.php');
            include($dir.D.'core'.D.'static'.D.'Rpc_client.php');
        }
        spl_autoload_register('autoload');

        //实例化curl-post数据传输类库
        $curl_post = new Curl;
        //实例化curl-get数据传输类库
        $curl_get = new Curl_get;

        //调用route方法运行路由重定向及重置目录名
        if(ROUTE_RUN==1){
            include($dir.D.'core'.D.'route.php');
            core\route_rewrite();
            core\mvp_dir_rewrite($dir);
            core\model_dir_rewrite($dir);
            core\presenter_dir_rewrite($dir);
            exit('路由配置成功,请在config.route.php配置文件中关闭该配置!<br /><br />以下为当前路由URL地址,路由URI请阅读开发手册路由部分:<br /><br />服务器支持htaccess : '.API_URL.'model/param/key=value<br /><br />服务器不支持htaccess : '.API_URL_OTHER.'model/param/?key=value');
        }

        //异常日志记录
        function onerror($message,$path=null){
            if($path===null){
                $error = date('y-m-d h:i:s',time()).$message.PHP_EOL;
                error_log($error,3,'..'.D.'core'.D.'log'.D.'errors.log');
            }else{
                $error = date('y-m-d h:i:s',time()).$message.PHP_EOL;
                error_log($error,3,$path);
            }
        }

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
        @$href = $_GET['href'];
		if(in_array($href,$new_arr)){
		    if(file_exists($dir.D.'application'.D.'view'.D.$href.'.php')){
                include($dir.D.'application'.D.'view'.D.$href.'.php');
            }else{
                include($dir.D.'application'.D.APPLICATION_RENAME[5].'_view'.D.$href.'.php');
            }
		}else{
		    if(file_exists($dir.D.'application'.D.'view'.D.'start.php')){
                include($dir.D.'application'.D.'view'.D.'start.php');
            }else{
                include($dir.D.'application'.D.APPLICATION_RENAME[5].'_view'.D.'start.php');
            }
		}
        closedir($view_fopen);
    }
    //API数据回传运行基类
    public static function api_run($ob_inter,$response){
        $ob_inter->state($response[0],$response[1],$response[2]);
    }
}