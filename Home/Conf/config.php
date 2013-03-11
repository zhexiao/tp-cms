<?php
//数据库配置文件
$arr1 = require './Config/config.inc.php';

//前台设置
$arr2 = require './Config/frontstage.inc.php';

$arr3 = array(

);
return array_merge($arr1,$arr2,$arr3);
?>