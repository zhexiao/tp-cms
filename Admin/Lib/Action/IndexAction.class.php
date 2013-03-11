<?php
class IndexAction extends Action {
	/**
	 * 显示登陆首页
	 */
    function index(){
        $this->display();
    }
    
    /**
     * 生成登陆首页验证码
     */
    function verify(){	
 		create_verify();
    }
    
    /**
     * 验证用户名，密码是否正确并存入SESSION中
     */
    function check(){
    	//得到用户名,密码,验证码
    	$username = trim($_POST['username']);
    	$password = trim($_POST['password']);
    	$verify   = trim($_POST['verify']);
    	
    	if($_SESSION['verify'] != strtoupper($verify) ){
    		$this->error('验证码出错');
    	}
    	//实例化用户模型
    	$user = new UserModel();
    	$userinfo = $user->getByUsername($username);
    	if(count($userinfo) > 0){
    		if($userinfo['password'] != md5($password)){
    			$this->error('密码错误，请重试');
    		}else if($userinfo['active'] != 1){
    			$this->error('账户未激活,请联系管理员');
    		}else{
    			//得到当前时间
    			 $current_time = date('Y-m-d H:i:s',time());
    			 //判断用户写入
    			 $where['username'] = $username;
    			 
    			//如果用户登录进来了，把登录时间写进数据库login_record中。
    			$log_record = new LoginRecordModel();
    			$log['login_date'] = 	$current_time;
    			$log['email']		=	$userinfo['email'];
    			$log['username']	=	$userinfo['username'];
    			$return_id = $log_record->where($where)->add($log);
    			//将插入返回的主键ID 保存在SESSION中，以便在用户退出的时候更新退出时间
    			$_SESSION['record_id'] = $return_id;
    			
    			//将用户放进SESSION里面，并且更新用户最后登陆时间
    			$_SESSION['username'] = trim($username);   
    			$data['last_login_date'] = $current_time;   		
    			$user->where($where)->save($data);
    			
    			$this->redirect('Manage/index');
    		}
    	}else{
    		$this->error('您输入的用户名不存在');
    	}
    	
    }
    
	
	/**
	 * logout页面
	 */
	function logout(){
		//更新用户退出时间
		$log_record = new LoginRecordModel();
		$current_time = date('Y-m-d H:i:s',time());
		$log['logout_date'] = $current_time;
		//如果是ajax传来的值
		if( !empty($_POST['record_id']) ){						
			$log_record->where('id='.$_POST['record_id'])->save($log);
		}else{
		//如果是点击logout退出
			$log_record->where('id='.$_SESSION['record_id'])->save($log);		
			unset($_SESSION['username']);
			$this->assign('jumpUrl',__APP__.'/Index/index');
			$this->success('退出成功');
		}	
	
	}
}
?>