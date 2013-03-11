<?php
class MenuAction extends CommonAction{
	/**
	 * 菜单项 显示页面
	 */
	function index(){
		//查找出子菜单，并且显示分配在左侧菜单中
		$menus = new MenuModel();
		$menu_list = $menus->select();
		$this->assign('menulist',$menu_list);
		
		import('ORG.Util.Page');
		$menus = new MenuModel();
		//判断是否有搜索
		$keyword = trim($_POST['keyword']);
		/*
		$ftype = trim($_POST['ftype']);
		if(!empty($keyword) && !empty($ftype)){
			//如果查询的是发布字段（因为发布字段和排序都是以整数来排列的）
			if ($ftype == 'id'){
				$where[$ftype] = array('eq',$keyword);
			}else{
				$where[$ftype] = array('like','%'.$keyword.'%');
			}			
		}
		*/
		if(is_numeric($keyword)){
			$where['id'] = array('eq',$keyword);
		}else{
			$where['title'] = array('like','%'.$keyword.'%');
		}
		
		//切换状态
		$change_pub = trim($_POST['change_published']);
		if($change_pub){
			$where['published'] = array('eq',$change_pub);
		}
		//传值在前台判断
		$this->assign('change_pub',$change_pub);
		
		//得到分页需要的总文章数
		$count = $menus->where($where)->count();
		$page = new Page($count,C('PAGESIZE'));
		$show = $page->show();
		$this->assign("show",$show);
		//得到文章数据
		$list = $menus->where($where)->order('id')->limit($page->firstRow.','.$page->listRows)->select();	
		$this->assign('mlist',$list);
		//分配配置中的每页显示多少文章到前台
		$this->assign('pagesize',C('PAGESIZE'));
		$this->display();
	}
	
	/**
	 * 菜单项 新增页面
	 */
	function add(){
		//查找出子菜单，并且显示分配在左侧菜单中
		$menus = new MenuModel();
		$menu_list = $menus->select();
		$this->assign('menulist',$menu_list);
		
		$this->display();
	}
	
	/**
	 * 菜单项   插入数据库
	 */
	function insert(){
		$menu = new MenuModel();
		//如果压入成功
		if( ($data = $menu->create()) !== false ){
			if( $menu->add() ){
				$this->assign('jumpUrl',__URL__.'/index');
				$this->success('添加新菜单项成功');
			}else{
				$this->assign('jumpUrl',__URL__.'/index');
				$this->error('添加失败'.$data->getError());
			}
		}else{
			$this->assign('jumpUrl',__URL__.'/index');
			$this->error('添加失败'.$menu->getError());
		}
	}
	
	/**
	 * 菜单项  编辑页面
	 */
	function edit(){
		//查找出子菜单，并且显示分配在左侧菜单中
		$menus = new MenuModel();
		$menu_list = $menus->select();
		$this->assign('menulist',$menu_list);
		
		$menu = new MenuModel();
		$menu_id = $_GET['id'];
		//动态获得数据
		$list = $menu->getById($menu_id);
		//分配数据
		$this->assign('mlist',$list);
		
		$this->display();
	}
	
	/**
	 * 菜单项 编辑后更新数据库
	 */
	function update(){
		$menu = new MenuModel();
		if( ($data = $menu->create()) !== false ){
			//如果传了主键ID过来了，这个是我隐形hidden传的
			if( !empty($data['id']) ){
				$menu->save($data);
				$this->assign('jumpUrl',__URL__.'/index');
				$this->success('菜单更新成功');
			}else{
				$this->assign('jumpUrl',__URL__.'/index');
				$this->error('请选择菜单项');
			}
		}else{
				$this->assign('jumpUrl',__URL__.'/index');
				$this->error('数据出错'.$menu->getError());
		}
	}
	
	/**
	 * 删除菜单项
	 */
	function del(){
		//序列化主键Id为：1,2,3,...以便批量删除
		$del_id = $_POST['del_id'];
		$str = implode($del_id, ',');
		//实例化
		$menu = new MenuModel();
		//删除文章
		if($menu->delete($str)){
			//同时删除其下属子菜单
			$menu_items = new MenuItemModel();
			$where = array(
				'menuid' => array('in',$del_id),
			);
			$menu_items->where($where)->delete();
			
			$this->assign('jumpUrl',__URL__.'/index');
			$this->success('菜单删除成功~~');
		}else{
			$this->assign('jumpUrl',__URL__.'/index');
			$this->error('删除失败');
		}
	}
}

?>