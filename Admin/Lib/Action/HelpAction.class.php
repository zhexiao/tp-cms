<?php
class HelpAction extends CommonAction{
	/**
	 * 显示页面
	 */
	function index(){
		$this->display();
	}
	
	/**
	 * 发送完成
	 */
	function send_email(){
		$email_to = trim($_POST['email_to']);
		$title = trim($_POST['title']);
		$message = trim($_POST['description']);
		
		if( empty($title) || empty($message) ){	
			$this->error("发送失败，请填写完全内容！");	
		}else{
			if( SendMail($email_to, $title, $message) !== false){
				//发送警告成功，则将用户的警告数加1
				$this->assign("jumpUrl",__APP__."/Block/index");			
				$this->success("发送成功!");
			}else{		
				$this->error("发送失败,请联系管理员！！！");
			}
		}
	}
}
?>