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
			<div class="step"><span style="color: #F48B06;">3 : 数据库设定</span></div>
			<div class="step">4 : 网站设置</div>
			<div class="step">5 : 完成</div>					
			<div class="box"></div>
		</div>
	
		<div id="rightpad">
			<div id="step">
				<h2>数据库设置</h2>
				<div class="wrap_a">
					<form action="web_con.php" method="post">
						<input type="submit" value="下一步" name="sub" class="wrap_sub"/>
				</div>
			</div>
			
			<div id="right_content">
				<div class="content_2">
					
						<p class="tbody_td website_tr"><span class="website_title">主机名称:</span>
							<input type="text" value="localhost" name="DB_HOST"  class="inputt1">
						</p>
						<p class="tbody_td website_tr"><span class="website_title">数据库类型:</span>
							<input type="text" value="mysql" name="DB_NAME"  class="inputt1">
						</p>
						<p class="tbody_td website_tr"><span class="website_title">数据库名称:</span>
							<input type="text" value="" name="DB_NAME"  class="inputt1">
						</p>
						<p class="tbody_td website_tr"><span class="website_title">数据库用户名:</span>
							<input type="text" value="root" name="DB_USER"  class="inputt1">
						</p>
						<p class="tbody_td website_tr"><span class="website_title">数据库密码:</span>
							<input type="text" value="" name="DB_PWD"  class="inputt1">
						</p>
						<p class="tbody_td website_tr"><span class="website_title">数据库前缀:</span>
							<input type="text" value="xz_" readonly="readonly" name="DB_PREFIX"  class="inputt1">
						</p>
						<p class="tbody_td website_tr"><span class="website_title">数据库字符集:</span>
							<input type="text" value="utf8" readonly="readonly" name="DB_CHARSET"  class="inputt1">
						</p>
						<p class="tbody_td website_tr"><span class="website_title">数据库端口:</span>
							<input type="text" value="3306" name="DB_PORT"  class="inputt1">
						</p>
						<p class="tbody_td website_tr"><span class="website_title">数据库部署方式:</span>
							<input type="text" value="0" readonly="readonly" name="DB_DEPLOY_TYPE"  class="inputt1">
						</p>
						<p class="tbody_td website_tr"><span class="website_title">导入测试数据:</span>
							导入<input type="radio" value="1" name="test" checked="checked" >
							&nbsp;不导入<input type="radio" value="2" name="test" >
						</p>
						
					</form>
				</div>
			</div>
		
	</div>
</div>
</body>
</html>