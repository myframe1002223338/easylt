<?php
namespace core;
function route_param(){
	$query = explode('/',$_SERVER['REQUEST_URI']);
    //var_dump($query);
	//请在config_route.php中配置$query下标参数,默认不配置;
    $GLOBALS['query_model'] = strtolower($query[OS_MODEL]);//获取API-URL中model模型文件名用于路由分发
    $GLOBALS['query_param'] = strtolower($query[OS_PARAM]);//获取API-URL中的参数用于判断model模型文件下多接口业务分发对接
}
function route_rewrite(){
    //加载路由重写内容并写入.htaccess
    if(APPLICATION_RENAME[2]!='presenter'){
        $presenter = APPLICATION_RENAME[2].'_presenter';
    }else{
        $presenter = APPLICATION_RENAME[2];
    }
    if(APPLICATION_RENAME[3]!='controller'){
        $controller = APPLICATION_RENAME[3].'_controller';
    }else{
        $controller = APPLICATION_RENAME[3];
    }
    $htaccess = '<ifmodule mod_rewrite.c>
                 RewriteEngine on 
                 RewriteRule ^$ public/index.php
                 RewriteRule ^'.APPLICATION.'/'.PRESENTER.'/'.CONTROLLER.'/index/(.+)/(.+)/(.+)$ application/'.$presenter.'/'.$controller.'/index.php/$1/$2/?$3
                 RewriteRule ^'.APPLICATION.'/'.PRESENTER.'/'.CONTROLLER.'/index/(.+)/(.+)$ application/'.$presenter.'/'.$controller.'/index.php/$1/?$2
                 RewriteRule ^'.APPLICATION.'/'.PRESENTER.'/'.CONTROLLER.'/index/(.+)$ application/'.$presenter.'/'.$controller.'/index.php/$1
                 RewriteRule ^href/(.+)/(.+)$  ?href=$1&$2
                 RewriteRule ^href/(.+)$  ?href=$1
                 </ifmodule>';
    $fp = fopen('..'.D.'.htaccess','w');
    fwrite($fp,$htaccess);
    fclose($fp);
}
function mvp_dir_rewrite($dir){
    $open_dir = scandir($dir.D.'application');
    foreach($open_dir as $value){
        $dirname = iconv('gbk','utf-8',$value);
        if(strpos($dirname,'_')){
            $dirname_parse = explode('_',$dirname);
            $last_value = array_pop($dirname_parse);
            if($last_value=='model'){
                if(APPLICATION_RENAME[0]!='model'){
                    rename('..'.D.'application'.D.$dirname,'..'.D.'application'.D.APPLICATION_RENAME[0].'_model');
                }else{
                    rename('..'.D.'application'.D.$dirname,'..'.D.'application'.D.APPLICATION_RENAME[0]);
                }
            }
            if($last_value=='presenter'){
               if(APPLICATION_RENAME[2]!='presenter'){
                   rename('..'.D.'application'.D.$dirname,'..'.D.'application'.D.APPLICATION_RENAME[2].'_presenter');
               }else{
                   rename('..'.D.'application'.D.$dirname,'..'.D.'application'.D.APPLICATION_RENAME[2]);
               }
            }
            if($last_value=='view'){
                if(APPLICATION_RENAME[5]!='view'){
                    rename('..'.D.'application'.D.$dirname,'..'.D.'application'.D.APPLICATION_RENAME[5].'_view');
                }else{
                    rename('..'.D.'application'.D.$dirname,'..'.D.'application'.D.APPLICATION_RENAME[5]);
                }
            }
        }elseif($dirname=='model' || $dirname=='presenter' || $dirname=='view'){
            if(APPLICATION_RENAME[0]!='model'){
                rename('..'.D.'application'.D.'model','..'.D.'application'.D.APPLICATION_RENAME[0].'_model');
            }
            if(APPLICATION_RENAME[2]!='presenter'){
                rename('..'.D.'application'.D.'presenter','..'.D.'application'.D.APPLICATION_RENAME[2].'_presenter');
            }
            if(APPLICATION_RENAME[5]!='view'){
                rename('..'.D.'application'.D.'view','..'.D.'application'.D.APPLICATION_RENAME[5].'_view');
            }
        }
    }
}
function model_dir_rewrite($dir){
    if(APPLICATION_RENAME[0]=='model'){
        $model = APPLICATION_RENAME[0];
    }else{
        $model = APPLICATION_RENAME[0].'_model';
    }
    $open_dir = scandir($dir.D.'application'.D.$model);
    foreach($open_dir as $value){
        $dirname = iconv('gbk','utf-8',$value);
        if(strpos($dirname,'_')){
            $dirname_parse = explode('_',$dirname);
            $last_value = array_pop($dirname_parse);
            if($last_value=='server'){
                if(APPLICATION_RENAME[1]!='server'){
                    rename('..'.D.'application'.D.$model.D.$dirname,'..'.D.'application'.D.$model.D.APPLICATION_RENAME[1].'_server');
                }else{
                    rename('..'.D.'application'.D.$model.D.$dirname,'..'.D.'application'.D.$model.D.APPLICATION_RENAME[1]);
                }
            }
        }elseif($dirname=='server'){
            if(APPLICATION_RENAME[1]!='server'){
                rename('..'.D.'application'.D.$model.D.$dirname,'..'.D.'application'.D.$model.D.APPLICATION_RENAME[1].'_server');
            }
        }
    }
}
function presenter_dir_rewrite($dir){
    if(APPLICATION_RENAME[2]=='presenter'){
        $presenter = APPLICATION_RENAME[2];
    }else{
        $presenter = APPLICATION_RENAME[2].'_presenter';
    }
    $open_dir = scandir($dir.D.'application'.D.$presenter);
    foreach($open_dir as $value){
        $dirname = iconv('gbk','utf-8',$value);
        if(strpos($dirname,'_')){
            $dirname_parse = explode('_',$dirname);
            $last_value = array_pop($dirname_parse);
            if($last_value=='controller'){
                if(APPLICATION_RENAME[3]!='controller'){
                    rename('..'.D.'application'.D.$presenter.D.$dirname,'..'.D.'application'.D.$presenter.D.APPLICATION_RENAME[3].'_controller');
                }else{
                    rename('..'.D.'application'.D.$presenter.D.$dirname,'..'.D.'application'.D.$presenter.D.APPLICATION_RENAME[3]);
                }
            }
            if($last_value=='logic'){
                if(APPLICATION_RENAME[4]!='logic'){
                    rename('..'.D.'application'.D.$presenter.D.$dirname,'..'.D.'application'.D.$presenter.D.APPLICATION_RENAME[4].'_logic');
                }else{
                    rename('..'.D.'application'.D.$presenter.D.$dirname,'..'.D.'application'.D.$presenter.D.APPLICATION_RENAME[4]);
                }
            }
        }elseif($dirname=='controller' || $dirname=='logic'){
            if(APPLICATION_RENAME[3]!='controller'){
                rename('..'.D.'application'.D.$presenter.D.$dirname,'..'.D.'application'.D.$presenter.D.APPLICATION_RENAME[3].'_controller');
            }
            if(APPLICATION_RENAME[4]!='logic'){
                rename('..'.D.'application'.D.$presenter.D.$dirname,'..'.D.'application'.D.$presenter.D.APPLICATION_RENAME[4].'_logic');
            }
        }
    }
}