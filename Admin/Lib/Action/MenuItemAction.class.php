<?php
class MenuItemAction extends CommonAction{
	/**
	 * 某菜单项下的 详细菜单列表（如 主菜单下有哪几个菜单）
	 * 首先 显示主菜单下面的子菜单
	 */
	function index(){
		//接受传来的菜单项ID用来获得该菜单下的 子菜单 
		$menu_id = $_REQUEST['id'];
		//将menuid存入SESSiON以便后面在子菜单中使用
		$_SESSION['menuid'] = $menu_id;

		$menu = new MenuModel();
		//查询该菜单项名称
		$list = $menu->getById($menu_id);
		$this->assign('mlist',$list);
		
		//查找出子菜单，并且显示分配在左侧菜单中
		$menus = new MenuModel();
		$menu_list = $menus->select();
		$this->assign('menulist',$menu_list);
		
		//导入分页类
		import('ORG.Util.Page');
		//实例化模型
		$menu_items = new MenuItemModel();
	
		//添加where语句找到该menu_id相对应的子菜单
		$where['menuid'] = $menu_id;
		
		
		
		//获取分页总数
		$count = $menu_items->where($where)->count();
		//完成分页
		$page = new Page($count, C('PAGESIZE'));
		$show = $page->show();
		$this->assign("show",$show);
		
		$list = $menu_items->order('id')->where($where)->limit($page->firstRow.','.$page->listRows)->select();
		$this->assign('milist',$list);
		$this->display();
	}
	
	/**
	 *  添加新的子菜单，但是要选择是 哪种类型(Article,Category,Section).
	 *  从组件中找出存在的组建
	 */
	function add(){	
		//查找出子菜单，并且显示分配在左侧菜单中
		$menus = new MenuModel();
		$menu_list = $menus->select();
		$this->assign('menulist',$menu_list);
		
		//导入分页类
		import("ORG.Util.Page");
		//查询组件，相当于哪几种类型
		$component = new ComponentModel();
		$where = array(
			'enabled'=>1
		);
		$list = $component->where($where)->select();
		$this->assign('comlist',$list);
		
		//得到类型
		$link = strtolower($_REQUEST['link']);
		//查询数据,如果默认为空，则显示文章列表
		if(empty($link) || $link == 'article'){
			$art = new ArticleModel();
			$count = $art->count();
			$page = new Page($count,C("PAGESIZE"));
			//完成分页
			$show = $page->show();
			//查询分页数据
			$list = $art->order('id desc')->limit($page->firstRow.','.$page->listRows)->select();
					
		}else if($link == 'category'){
			$cat = new CategoryModel();
			$count = $cat->count();
			$page = new Page($count,C("PAGESIZE"));
			//完成分页
			$show = $page->show();
			//查询分页数据
			$list = $cat->order('id desc')->limit($page->firstRow.','.$page->listRows)->select();
			
		}else{
			$sec = new SectionModel();
			$count = $sec->count();
			$page = new Page($count,C("PAGESIZE"));
			//完成分页
			$show = $page->show();
			//查询分页数据
			$list = $sec->order('id desc')->limit($page->firstRow.','.$page->listRows)->select();
		}
		
		
		$this->assign('show',$show);
		$this->assign('list',$list);
		$this->assign('link',$link);
		
		$this->display();
	}
	
	/**
	 * 添加新子菜单 到选中的菜单中
	 */
	function addin(){		
		//父菜单ID
		$menu_id = $_SESSION['menuid'];
		//首字母大写 例：Article
		$type = ucfirst($_POST['type']);
		$id = $_POST['creat_id'];
		//查询数据,实例化对象
		if($type == 'Article'){
			$obj = new ArticleModel();
		}else if($type == 'Category'){
			$obj = new CategoryModel();
		}else{
			$obj = new SectionModel();
		}
		$title = $obj->field('title,description')->find($id);
		//重写链接
		$link = $id;
		
		//得到所有父菜单，然后在模板中判断选择
		$menus = new MenuModel();
		$menu_list = $menus->select();
		
		//得到该父菜单下的所有子菜单
		$menu_items = new MenuItemModel();
		$where = array(
			'menuid'=>$menu_id
		);
		$m_items = $menu_items->where($where)->select();
		
		//查询组建,得到相对应组建ID
		$components = new ComponentModel();
		$coms = $components->select();
		$count = count($coms);
		for($i=0;$i<$count;$i++){
			if( $coms[$i]['link'] == $type){
				$componentid = $coms[$i]['id'];
			}
		}
		
		
		$this->assign("type_id",$id);
		$this->assign('menu_id',$menu_id);
		$this->assign('link',$link);
		$this->assign('title',$title);
		$this->assign('menu_list',$menu_list);
		$this->assign('m_items',$m_items);
		$this->assign('type',$type);
		$this->assign('componentid',$componentid);
		
		$this->display();
	}
	
	/**
	 * 将选中的新子菜单插入数据库menu_item中
	 */
	function insert(){
		//父菜单ID
		$menu_id = $_SESSION['menuid'];	
		$menu_item = new MenuItemModel();
		
		//如果有上传的图片
		if( $_FILES['upload_img']['size'] > 0 ){
			//得到上传函数返回的信息
			$get_upload_info = $this->upload_img();
			
			$image_src = $get_upload_info[0]['savename'];
		}
		
		//插入数据库
		if( ($data = $menu_item->create()) !== FALSE ){
			//如果文件名存在,添加图片路径进来
			if( !empty($image_src) ){
				$data['image_src'] = $image_src;
			}		
			
			if($menu_item->add($data)){
				$this->assign("jumpUrl",__URL__.'/index/id/'.$menu_id);
				$this->success('添加新菜单成功~~~~~');
			}else{
				$this->assign("jumpUrl",__APP__.'/Menu/index');
				$this->error('添加失败!!!!'.$menu_item->getError());
			}
		}
		
	}
	
	/**
	 * 上传图片的函数
	 */
	function upload_img(){
		import("ORG.Net.UploadFile");
		$upload = new UploadFile();
		$upload->savePath = "./Public/Uploads/";
		$upload->maxSize=1000000;
		//uniqid不要加()
		$upload->saveRule = 'time';
		$upload->allowExts = array('jpg','jpeg','png','gif');
		$upload->allowTypes = array('image/png','image/jpg','image/gif','image/jpeg');
		
		//$upload->thumb = true;
		//$upload->thumbMaxWidth='100,500';  //以字串格式来传，如果你希望有多个，那就在此处，用,分格，写上多个最大宽
		//$upload->thumbMaxHeight='100,500';	//最大高度
		//$upload->thumbPrefix='s_,m_';		//缩略图文件前缀
		
		//准备好了上传
		if($upload->upload()){
			//上传成功后一定要返回成功的文件信息
			return $upload->getUploadFileInfo();
		}else{
			$this->error($upload->getErrorMsg());
		}
	}
	
	/**
	 * 编辑子菜单
	 */
	function edit(){
		//父菜单ID
		$menu_id = $_SESSION['menuid'];
		//得到子菜单Id
		$menuitem_id = $_GET['id'];
		//得到数据
		$menuitem = new MenuItemModel();
		$menuitems = $menuitem->getById($menuitem_id);	
		
		//得到所有父菜单，然后在模板中判断选择
		$menus = new MenuModel();
		$menu_list = $menus->select();
		
		//得到该父菜单下的所有子菜单
		$menu_items = new MenuItemModel();
		$where = array(
			'menuid'=>$menu_id
		);
		$m_items = $menu_items->where($where)->select();
		
		$this->assign('itemslist',$menuitems);
		$this->assign('menu_list',$menu_list);
		$this->assign('m_items',$m_items);
		
		$this->display();
	}
	
	/**
	 * 更新编辑的子菜单
	 */
	function update(){
		$menuitem = new MenuItemModel();
		//如果有上传的图片
		if( $_FILES['upload_img']['size'] > 0 ){
			//得到上传函数返回的信息
			$get_upload_info = $this->upload_img();
			
			$image_src = $get_upload_info[0]['savename'];
		}		
		
		if( ($data = $menuitem->create()) !== false ){
			//如果有传ID
			if(!empty($data['id'])){
				
				//如果文件名存在,添加图片路径进来
				if( !empty($image_src) ){
					$data['image_src'] = $image_src;
				}
				
				if($menuitem->save($data)){
					$this->assign("jumpUrl",__APP__.'/Menu/index');
					$this->success('更新菜单成功~~~~~');
				}else{
					$this->assign("jumpUrl",__APP__.'/Menu/index');
					$this->error('更新菜单失败！！！！'.$menuitem->getError());
				}
			}
		}
	}
	
	/**
	 * 删除子菜单
	 */
	function del(){
		$delid = implode($_POST['del_id'], ',');
		$menuitem = new MenuItemModel();
		if($menuitem->delete($delid)){
			$this->assign('jumpUrl',__APP__.'/Menu/index');
			$this->success('子菜单删除成功');
		}else{
			$this->assign("jumpUrl",__APP__.'/Menu/index');
			$this->error('更新菜单失败！！！！'.$menuitem->getError());
		}
	}
}
?>