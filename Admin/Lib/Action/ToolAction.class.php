<?php
/**
 * 工具菜单处理页面
 */
class ToolAction extends CommonAction{		
	/**
	 * 调用清除缓存数据
	 */
	function clear_cache(){
		//调用common里面的delete_dir()函数删除目录
		$dir = RUNTIME_PATH;
		//由于Runtime_path默认为 "./admin/Runtime/",我们必须删除最后的 “/”
		$dir = substr( $dir, 0, strlen($dir)-1 );
		if(delete_dir($dir)){
			//清空前台缓存
			$front_dir = './Home/Runtime';
			delete_dir($front_dir);
			//清空静态缓存目录
			delete_dir(HTML_PATH);
			//调用显示模板
			$this->assign("jumpUrl","__APP__/Manage/index");			
			$this->success("缓存清除成功，页面跳转中~~~");			
		}else{
			$this->assign("jumpUrl","__APP__/Manage/index");			
			$this->error("缓存清除失败，返回控制面板！");	
		}
	}
	
	/**
	 * 备份数据库 数据
	 */
	function backup_db(){
		//导入数据库类
		import("Think.Db.Db");
		//实例化数据，返回数据库驱动类
		$db =   DB::getInstance();  
		//得到本数据库中的所有表
		$tables = $db->getTables();
		
		$sqls = '';
		foreach ($tables as $table_name){  
			//注意，我修改了分隔符为 ;^
			//表结构
			$sqls .= "DROP TABLE IF EXISTS `$table_name`;^\n";
			$struct = $db->query("show create table `$table_name`");
			$sqls .= $struct[0]['Create Table'].";^\n\n";  
				
			//得到没有前缀的表名，其实就是模型名用来实例化读取数据库数据
			$model_name = str_replace(C('DB_PREFIX'),'',$table_name);  
			$model = D($model_name);  
			$lists = $model->select(); 
			   
			//得到SQL数据
			foreach ($lists as $rows) {  
				$data = "INSERT INTO `{$table_name}` VALUES (";  
				foreach($rows as $value) {  
					$data .="'".mysql_real_escape_string($value)."',";  
				}  
			//去掉最后的一个 , 号
			$data = substr($data,0,-1);  
			$data .= ");^\n\n";  
			$sqls .= $data;   
			}   
		}		
		
		//将数据写入文件
		$filename= 'db_'.date("Y-m-d-H-i-s").'.sql';	
		$filepath = './Public/Backup/'.$filename;
		$handle = fopen($filepath,'w');   
	  
		if(	fwrite($handle, $sqls) === false){
			$this->assign("jumpUrl","__APP__/Manage/index");			
			$this->error("备份失败，请联系管理员");	
		}else{
			$this->assign("jumpUrl","__APP__/Manage/index");			
			$this->success("备份成功，数据保存在/Public/Backup/文件夹下");
		}
		fclose($handle);		
	}
	
	/**
	 * 登录状态
	 */
	function log_record(){
		//导入分页类
		import('ORG.Util.Page');
		//搜索选项
		$keyword = trim($_POST['keyword']);
		//判断是电子邮件还是用户名。
		$pos = stripos($keyword, '@');
		if( $pos !== false ){
			$where = array(
				'email'		=>	array('like','%'.$keyword.'%')
			);
		}else{
			$where = array(
				'username'	=>	array('like','%'.$keyword.'%')
			);
		}
		
		//切换警告状态
		$change_pub = trim($_POST['change_published']);
		if($change_pub == 1){
			$where['warn_count'] = array('gt',0);
		}else if($change_pub == 2){
			$where['warn_count'] = array('eq',0);
		}
		//传值在前台判断
		$this->assign('change_pub',$change_pub);

		//得到所有登录记录
		$log_record = new LoginRecordModel();
		$counts = $log_record->where($where)->count();
		$page = new Page($counts, C('PAGESIZE'));
		$show = $page->show();
		$list = $log_record->order('id desc')->where($where)->limit($page->firstRow.','.$page->listRows)->select();
		
		$this->assign('show',$show);
		$this->assign('list',$list);
		
	
		
		
		$this->display();		
	}
	
	/**
	 * 发送Email 警告用户
	 * 显示发送页面
	 */
	function warn_user(){
		$log_record = new LoginRecordModel();
		$username = trim($_POST['user_record_name']);
		$userinfo = $log_record->getByUsername($username);
		$email = $userinfo['email'];
		$id	= $userinfo['id'];
		
		$this->assign('emal_to',$email);
		$this->assign('id',$id);
		$this->display();	
	}
	
	/**
	 * 发送邮件 页面 
	 * 完成页面
	 */
	function send_email(){
		$id = trim($_POST['id']);
		$email_to = trim($_POST['email_to']);
		$title = trim($_POST['title']);
		$message = trim($_POST['description']);
		
		if( empty($title) || empty($message) ){	
			$this->error("发送失败，请填写完全内容！");	
		}else{
			if( SendMail($email_to, $title, $message) !== false){
				//发送警告成功，则将用户的警告数加1
				$log_record = new LoginRecordModel();
				$userinfo = $log_record->getById($id);
				$warn_count = $userinfo['warn_count'];
				$data['warn_count'] = $warn_count + 1;				
				$log_record->where('id='.$id)->save($data);
				
				$this->assign("jumpUrl","__APP__/Tool/log_record");			
				$this->success("发送成功!");
			}else{
				$this->assign("jumpUrl","__APP__/Tool/log_record");			
				$this->error("发送失败,请联系管理员！！！");
			}
		}
		
	}
	
	
	/**
	 * 生成RSS
	 */
	function generate_rss(){
		//得到每一天所有的最新文章
		$article = new ArticleModel();
		$where = array(
			'created'	=>	array('gt',date("Y-m-d")),
		);
		$list = $article->where($where)->select();
		
		//定义生成RSS的字符串
		$str = '';
		$str .= '<?xml version="1.0" encoding="utf-8"?>
					<rss version="2.0">
						<channel>
							<title>'.C('SITENAME').'</title>
							<link>'.C('SITEURL').'</link>
							<keywords>'.C('SITE_KEYWORDS').'</keywords>
							<description>'.C('SITE_DESCRIPTION').'</description>';
				foreach ($list as $art){
					$str .= '
							<item>
								<title>'.$art['title'].'</title>
								<link>'.C('SITEURL').'/Article/view/id/'.$art['id'].'</link>
								<description>'.$art['description'].'</description>
								<pubDate>'.$art['created'].'</pubDate>
							</item>';
				}
						$str .='
							</channel>
						</rss>
						';	

		//将数据写入文件
		$handle = fopen('rss.xml', 'w');	
		if( fwrite($handle, $str) !== false ){
			$this->assign("jumpUrl","__APP__/Manage/index");		
			$this->success('RSS文件写入成功,文件路径为根目录');
		}else{
			$this->assign("jumpUrl","__APP__/Manage/index");		
			$this->error('RSS文件写入失败,请联系管理员');
		}		
		fclose($handle);
	}
	
}



?>