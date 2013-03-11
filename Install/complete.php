<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>网站安装程序</title>
<link type="text/css" rel="stylesheet" href="../Public/Home/Css/install.css" />
</head>
<body>
<?php 
if( empty($_POST['sub']) ){
	die('请按顺序来');
}else{
		//定义将要写入文件的字符串
		$str = "<?php \n //前台信息设置文件  \n return array( ";
		
		foreach ($_POST as $key=>$val){
			if( ($key !== 'login') && ($key !== '__hash__') && ($key !== 'test') && ($key !== 'sub')){
				//如果值是数字，可能密码也是数字，但一定要大于5位，则不要加引号,如果是Boolean值，也不要加引号
				if( (is_numeric($val) && strlen($val) < 5) || ($val === 'false'|| $val === 'true')){					
					$str .= " \n \t '$key'	=>	$val, \n ";		
				//如果是字符串，则加上				
				}else{
					$str .= " \n \t '$key'	=>	'$val', \n ";		
				}			
			}
		}		
		$str .= "); \n ?> ";
		
		$file = fopen("../Config/front_info.inc.php", "w");
		fwrite($file,$str);
		fclose($file);	
}
?>
<div id="header">
	<span class="logo"></span>
	<h1>AndyCMS 安装</h1>
</div>
<div id="content_box">
	<div id="content_pad">
		<div id="stepbar">
			<h2>安装步骤</h2>
			<div class="step ">1 : 安装前检查</div>
			<div class="step" >2 : 许可协议</div>
			<div class="step">3 : 数据库设定</div>
			<div class="step">4 : 网站设置</div>
			<div class="step"><span style="color: #F48B06;">5 : 完成</span></div>	
			<div class="box"></div>				
		</div>
	
		<div id="rightpad">
			<div id="step">
				<h2>完成</h2>
				<div class="wrap_a" style="width:200px;">					
					<input type="button" value="前台" onclick="window.location.href='../index.php'" name="sub" class="wrap_sub"/>
					<input type="button" value="后台" onclick="window.location.href='../admin.php'" name="sub" class="wrap_sub"/>
				</div>
			</div>
			
			<div id="right_content">
				<div class="content_2">
				</div>
			</div>
		
	</div>
</div>
</body>
</html>