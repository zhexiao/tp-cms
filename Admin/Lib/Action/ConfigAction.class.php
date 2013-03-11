<?php
class ConfigAction extends CommonAction{	
	/**
	 * 配置页	 
	 */
	function server(){
		$this->display();
	}
	
	/**
	 * 配置页面主内容
	 */
	function server_ct(){
		$this->display();
	}
	
	/**
	 * 更新传来的数据库资料数据
	 */
	function update(){
		//将传来的服务器数据更新	
		$str = "<?php \n //数据库配置文件  \n return array(" ;
		foreach ($_POST as $key=>$val) {;
			//如果传来的值是数据库数据而非Submit等
			if(stristr($key, "DB")){
				$str .="\n\t"."'$key'".'=>'."'$val'".",\n";
			}		
		}
		$str .= "); \n ?>";
		
		$file = fopen("./Config/config.inc.php", "w");
		if(fwrite($file,$str)){
			$this->assign('jumpUrl',__URL__.'/server');
			$this->success('更新成功');
		}else{
			$this->assign('jumpUrl',__URL__.'/server');
			$this->error('更新失败,请联系管理员');
		}
		fclose($file);		
	}
	
	/**
	 * 网站配置 设置
	 */
	function website(){		
		$this->display();
	}		
	
	/**
	 * 更新网站配置  数据
	 */
	function update_website(){
		//定义将要写入文件的字符串
		$str = "<?php \n //后台设置文件  \n return array( ";
		
		foreach ($_POST as $key=>$val){
			if( ($key !== 'login') && ($key !== '__hash__') ){
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
		
		$file = fopen("./Config/backstage.inc.php", "w");
		if(fwrite($file,$str)){
			$this->assign('jumpUrl',__URL__.'/website');
			$this->success('更新成功');
		}else{
			$this->assign('jumpUrl',__URL__.'/website');
			$this->error('更新失败,请联系管理员');
		}
		fclose($file);		
	}
	
	/**
	 * 前台设置
	 */
	function front_config(){
		//读取前台配置文章
		$front_config = F('frontstage.inc','','./Config/');
		$this->assign('front_config',$front_config);
		
		$this->display();
	}
	
	/**
	 * 更新前台设置
	 */
	function update_front_config(){
		//定义将要写入文件的字符串
		$str = "<?php \n //前台设置文件  \n return array( ";
		
		foreach ($_POST as $key=>$val){
			if( ($key !== 'login') && ($key !== '__hash__') ){
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
		
		$file = fopen("./Config/frontstage.inc.php", "w");
		if(fwrite($file,$str)){
			$this->assign('jumpUrl',__URL__.'/front_config');
			$this->success('更新成功');
		}else{
			$this->assign('jumpUrl',__URL__.'/front_config');
			$this->error('更新失败,请联系管理员');
		}
		fclose($file);		
	}
	
	/**
	 * 前台信息设置
	 */
	function front_info(){
		//读取前台信息配置文件
		$front_info = F('front_info.inc','','./Config/');
	
		$this->assign('front_info',$front_info);
		$this->display();
	}
	
	/**
	 * 更新前台信息设置
	 */
	function update_front_info(){
		//定义将要写入文件的字符串
		$str = "<?php \n //前台信息设置文件  \n return array( ";
		
		foreach ($_POST as $key=>$val){
			if( ($key !== 'login') && ($key !== '__hash__') ){
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
		
		$file = fopen("./Config/front_info.inc.php", "w");
		if(fwrite($file,$str)){
			$this->assign('jumpUrl',__URL__.'/front_info');
			$this->success('更新成功');
		}else{
			$this->assign('jumpUrl',__URL__.'/front_info');
			$this->error('更新失败,请联系管理员');
		}
		fclose($file);		
	}
}
?>