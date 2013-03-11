<?php
class UserAction extends CommonAction{
	/**
	 * 用户资料管理   首页
	 */
	function index(){
		//分页类
		import('ORG.Util.Page');
		
		//搜索栏
		$keyword = trim($_POST['keyword']);
		if(is_numeric($keyword)){
			$where['id'] = array('eq',$keyword);
		}else{
			$where['username'] = array('like','%'.$keyword.'%');
		}
		
		//切换用户状态
		$change_pub = trim($_POST['change_published']);
		if($change_pub){
			$where['active'] = array('eq',$change_pub);
		}
		//传值在前台判断
		$this->assign('change_pub',$change_pub);
		
		//查询数据并进行分页
		$users = new UserModel();
		$counts = $users->where($where)->count();
		$page = new Page($counts, C('PAGESIZE'));
		$show = $page->show();
		$this->assign('show',$show);
		$list = $users->where($where)->order('id')->limit($page->firstRow.','.$page->listRows)->select();
		
		$this->assign('user_lists',$list);
		$this->display();
	}
	
	/**
	 * 手动创建用户
	 */
	function add(){
		
		$this->display();
	}
	
	/**
	 * 将创建的用户插入数据库
	 */
	function insert(){
		//实例化对象并压入数据
		$users = new UserModel();
		if( !!$data = $users->create() ){
			if( $users->add() !== false){
				$this->assign('jumpUrl',__URL__.'/index');
				$this->success('用户添加成功');
			}else{
				$this->assign('jumpUrl',__URL__.'/add');
				$this->error('添加失败！！！'.$users->getDbError());
			}
		}else{
			$this->assign('jumpUrl',__URL__.'/add');
			$this->error('添加失败！！！'.$users->getError());
		}
	}
	
	/**
	 * 编辑用户信息
	 */
	function edit(){
		//得到传来的ID得到用户信息
		$user_id = trim($_GET['id']);
		$users = new UserModel();
		$list = $users->getById($user_id);
		$this->assign('user_list',$list);
		
		$this->display();
	}
	
	/**
	 * 更新用户信息
	 */
	function update(){
		$user = new UserModel();
		if( !!$data = $user->create() ){
			//通过隐藏表单传值ID，更新必须有个唯一主键
			if( !empty($data['id']) ){
				if( $user->save() ){
					$this->assign('jumpUrl',__URL__.'/index');
					$this->success('用户更新成功');
				}else{
					$this->assign('jumpUrl',__URL__.'/add');
					$this->error('更新失败！！！'.$user->getDbError());
				}
			}else{
				$this->assign('jumpUrl',__URL__.'/index');
				$this->error('请选择用户！！！'.$user->getDbError());
			}
		}else{
			$this->assign('jumpUrl',__URL__.'/index');
			$this->error('更新失败！！！'.$user->getError());
		}
	}
	
	/**
	 * 删除用户
	 */
	function del(){
		$del_id = $_POST['del_id'];
		$ids = implode($del_id, ',');
		//实例化
		$users = new UserModel();
		if( $users->delete($ids) ){
			$this->assign('jumpUrl',__URL__.'/index');
			$this->success('用户删除成功');
		}else{
			$this->assign('jumpUrl',__URL__.'/index');
			$this->error('更新失败！！！'.$users->getDbError());
		}
	}
	
	/**
	 * 修改个人资料
	 */
	function profile(){
		$user = new UserModel();
		$username = $_SESSION['username'];
		$userinfo = $user->getByUsername($username);
		
		$this->assign('userinfo',$userinfo);
		$this->display();
	}
	
	/**
	 * 更新 个人资料
	 */
	function update_profile(){
		$user = new UserModel();
		$username = $_SESSION['username'];
		$userinfo = $user->getByUsername($username);
		//如果旧密码正确
		if( md5($_POST['old_password']) == $userinfo['password'] ){
			if( !!$data = $user->create() ){
				if( $user->save() !== false ){
					$this->assign('jumpUrl',__APP__.'/Manage/index');
					$this->success('修改个人资料成功');
				}else{
					$this->assign('jumpUrl',__URL__.'/profile');
					$this->error('更新失败'.$user->getDbError());
				}
			}else{
				$this->assign('jumpUrl',__URL__.'/profile');
				$this->error('更新失败'.$user->getError());
			}
		}else{
			$this->assign('jumpUrl',__URL__.'/profile');
			$this->error('您输入的旧密码不正确');
		}
	}
}
?>