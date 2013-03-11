<?php
class CommonAction extends Action {
	//如果设置则自动加载
	function _initialize(){
		//如果是非法登录
		if(!$_SESSION['username']){
			$this->assign("jumpUrl",__APP__."/Index/index");
			$this->error('请登录');
		}			
		
		//开启SESSION
		session_start();
		//设置输出字符集
		header("Content-Type:text/html; charset=utf-8");
	
		//可能用户直接点击叉叉关闭页面，所以通过传个HIDDEN表单在top.html中用ajax传值给logout方法
		//这个功能有点不好想，因为不知道调用哪个函数  现在搞定了
		//主要调用的页面有    top.html  方法  logout
		$this->assign('record_id',$_SESSION['record_id']);
		
		//分配当前模块名 MODULE_NAME 用法在 left.html
		$this->assign('current_module',MODULE_NAME);
		
		
		//查找出子菜单，并且显示分配在左侧菜单中 ,用法在 left.html 显示子菜单
		$menus = new MenuModel();
		$menu_list = $menus->select();
		$this->assign('menulist',$menu_list);

	}
}
?>