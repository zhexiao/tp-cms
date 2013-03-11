<?php
class UserModel extends Model{
	//自动填充
	protected $_auto	=	array(
		array('active','get_active',1,'callback'),
		array('password','md5',3,'function'),
		array('reg_date','get_date',1,'callback'),
		array('last_login_date','get_date',1,'callback')
	);
	
	//自动验证
	protected $_validate	=	array(
		array('username','require','用户名必须填写'),
		array('username','','帐号名称已经存在！',0,'unique',1),
		array('password','require','密码必须填写'),
		array('repassword','password','二次输入的密码不同','0','confirm'),
		array('email','email','邮箱格式不正确'),
		
	);
	
	
	//如果有激活则激活，默认为非激活
	function get_active(){
		$active = $_POST['active'];
		if( $active == 1 ){
			return 1;
		}else{
			return 2;
		}
	}
	
	//得到当前时间
	function get_date(){
		return date('Y-m-d H:i:s',time());
	}
}
?>