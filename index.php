<?php
ini_set("display_errors",0);
define("THINK_PATH", './ThinkPHP');
define('APP_NAME', 'Home');
define('APP_PATH', './Home');

if( !file_exists('./Config/config.inc.php') ){
	header('Location:Install/install.php');
	exit();
}

//定义静态缓存目录文件
define('HTML_PATH','./list/'); 
//ALLINONE模式
define('RUNTIME_ALLINONE', true);  

//define('STRIP_RUNTIME_SPACE', false);
require THINK_PATH.'/ThinkPHP.php';

App::run();