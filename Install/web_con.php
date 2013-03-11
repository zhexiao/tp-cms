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
	if( empty($_POST['DB_HOST']) || empty($_POST['DB_NAME']) || empty($_POST['DB_USER'])  ){
		die('数据库填写不完整');
	}else{
		$mysqli = new mysqli($_POST['DB_HOST'],$_POST['DB_USER'],$_POST['DB_PWD'],$_POST['DB_NAME']);
		if (mysqli_connect_errno()) {
    		die('Connect Error (' . mysqli_connect_errno()  . ') '. mysqli_connect_error());
		}else{
			$str = "<?php \n //数据库配置文件  \n return array(" ;
			foreach ($_POST as $key=>$val) {;
				//如果传来的值是数据库数据而非Submit等
				if(stristr($key, "DB")){
					$str .="\n\t"."'$key'".'=>'."'$val'".",\n";
				}		
			}
			$str .= "); \n ?>";
			
			$file = fopen("../Config/config.inc.php", "w");
			fwrite($file,$str);
			fclose($file);	

			if($_POST['test'] == 1){
				$files = file_get_contents('./db.sql');
				$sqls = explode(';^', $files);
				foreach ($sqls as $ins_db){
					$mysqli->query("set names utf8");
					$mysqli->query($ins_db);
				}
			}
		}
	}
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
			<div class="step"><span style="color: #F48B06;">4 : 网站设置</span></div>
			<div class="step">5 : 完成</div>	
			<div class="box"></div>				
		</div>
	
		<div id="rightpad">
			<div id="step">
				<h2>网站设置</h2>
				<div class="wrap_a">
					<form action="complete.php" method="post">
						<input type="submit" value="下一步" name="sub" class="wrap_sub"/>
				</div>
			</div>
			
			<div id="right_content">
				<div class="content_2">
						<p class="tbody_td website_tr"><span class="website_title">网站标题:</span>
							<input type="text" value="" name="site_title"  class="inputt1">
						</p>
						<p class="tbody_td website_tr"><span class="website_title">网站关键字:</span>
							<input type="text" value="CMS" name="site_keywords"  class="inputt1">
						</p>
						<p class="tbody_td website_tr"><span class="website_title">网站关键描述:</span>
							<input type="text" value="" name="site_description"  class="inputt1">
						</p>
						<p class="tbody_td website_tr"><span class="website_title">头部滚动文章数量:</span>
							<input type="text" value="5" name="rollnum"  class="inputt1">
						</p>
						<p class="tbody_td website_tr"><span class="website_title">网站URL:</span>
							<input type="text" value="" name="site_url"  class="inputt1">
						</p>
						<p class="tbody_td website_tr"><span class="website_title">站长Email:</span>
							<input type="text" value="" name="admin_email"  class="inputt1">
						</p>
						<p class="tbody_td website_tr"><span class="website_title">站长QQ:</span>
							<input type="text" value="" name="admin_qq"  class="inputt1">
						</p>
						<p class="tbody_td website_tr"><span class="website_title">网站备份信息:</span>
							<input type="text" value="" name="web_backup"  class="inputt1">
						</p>
					</form>
				</div>
			</div>
		
	</div>
</div>
</body>
</html>