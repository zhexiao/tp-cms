<?php
//包含数据库配置文件
$arr1 = require './Config/config.inc.php';

//包含网站设置文件
$arr2 = require './Config/backstage.inc.php';

//个人配置
$arr3 = array(
	
);

//组合2个配置文件并返回
return array_merge($arr1,$arr2,$arr3);

?>