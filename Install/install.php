<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>网站安装程序</title>
<link type="text/css" rel="stylesheet" href="../Public/Home/Css/install.css" />
</head>
<body>
<div id="header">
	<span class="logo"></span>
	<h1>AndyCMS 安装</h1>
</div>
<div id="content_box">
	<div id="content_pad">
		<div id="stepbar">
			<h2>安装步骤</h2>
			<div class="step "><span style="color: #F48B06;">1 : 安装前检查</span></div>
			<div class="step" >2 : 许可协议</div>
			<div class="step">3 : 数据库设定</div>
			<div class="step">4 : 网站设置</div>
			<div class="step">5 : 完成</div>		
			<div class="box"></div>			
		</div>
	
		<div id="rightpad">
			<div id="step">
				<h2>安装前服务器环境检查</h2>
				<div class="wrap_a">
					<form action="license.php" method="post">
						<input type="submit" value="下一步" name="sub" class="wrap_sub"/>
					</form>
				</div>
			</div>
			
			
			<div id="right_content">
				<div class="content_1">
					<p class="content_1_p">
					如果这些条目中的 PHP 推荐设置某一项不被支持, 那么请采取措施纠正它们。如果不这么做，很可能您就无法完成  AndyCMS 的安装过程
					</p>
					<table class="check">
						<tr>
							<td><b>PHP配置</b></td>
							<td><b>本地配置</b></td>
						</tr>
						<tr>
							<td>PHP 版本 >= 5.2.4</td>
							<td>
								<?php 
								$pattern = "/PHP\/[0-9].[0-9].[0-9]{0,1}/";
								preg_match($pattern,$_SERVER['SERVER_SOFTWARE'],$php_info);
								echo $php_info[0];
								?>
							</td>
						</tr>
						<tr>
							<td>Apache 版本>= 2.2</td>
							<td>
								<?php 
									$pattern = "/Apache\/[0-9].[0-9].[0-9]{0,1}/";
									preg_match($pattern,$_SERVER['SERVER_SOFTWARE'],$apa_info);
									echo $apa_info[0];	
								?>
							</td>
						</tr>				
						<tr>
							<td>SESSION自动开启</td>
							<td>
								<?php 
									$ini_info = ini_get_all();
									if($ini_info['session.auto_start']['local_value'] == 0){
										echo '否';
									}else{
										echo '是';
									}
								?>
							</td>
						</tr>
						<tr>
							<td>缓存过期时间</td>
							<td>
								<?php 
									echo $ini_info['session.cache_expire']['local_value'].'秒';
								?>
							</td>
						</tr>
						<tr>
							<td>允许上传</td>
							<td>	
								<?php 
									if( ini_get('file_uploads') ==1 ){
										echo '允许';
									}else{
										echo '不允许';
									}
								?>
							</td>
						</tr>
						<tr>
							<td>上传文件夹大小</td>
							<td>	
								<?php 
									echo $ini_info['upload_max_filesize']['local_value'];
								?>
							</td>
						</tr>
						<tr>
							<td>内存缓存大小</td>
							<td>
								<?php 
									echo $ini_info['memory_limit']['local_value'];
								?>
							</td>
						</tr>
						<tr>
							<td>显示错误</td>
							<td>
								<?php 
									if( ini_get('display_errors') ==1 ){
										echo '显示';
									}else{
										echo '不显示';
									}
								?>
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
		
	</div>
</div>
</body>
</html>