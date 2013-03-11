<?php
class SectionAction extends CommonAction{
	/**
	 * 单元 显示首页
	 */
	function index(){
		import('ORG.Util.Page');
		$sections = new SectionModel();
		//判断是否有搜索
		$keyword = trim($_POST['keyword']);
		/*
		$ftype = trim($_POST['ftype']);
		if(!empty($keyword) && !empty($ftype)){
			//如果查询的是发布字段（因为发布字段和排序都是以整数来排列的）
			if ($ftype == 'published' || $ftype == 'order' || $ftype == 'id'){
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
		$count = $sections->where($where)->count();
		$page = new Page($count,C('PAGESIZE'));
		$show = $page->show();
		$this->assign("show",$show);
		//得到文章数据
		$list = $sections->where($where)->order('id DESC')->limit($page->firstRow.','.$page->listRows)->select();	
		$this->assign('slist',$list);
		//分配配置中的每页显示多少文章到前台
		$this->assign('pagesize',C('PAGESIZE'));
		$this->display();
	}
	
	/**
	 * 单元  添加新单元页面
	 */
	function add(){
		//显示添加页面名称
		$this->display();
	}
	
	/**
	 * 单元   新单元插入数据库
	 */
	function insert(){
		$section = new SectionModel();
		//如果数据压入成功
		if(( $data = $section->create() ) !== false){
			if ($section->add()){
				$this->assign('jumpUrl',__URL__.'/index');
				$this->success('添加新单元成功');
			}else{
				$this->assign('jumpUrl',__URL__.'/index');
				$this->error('添加新单元失败'.$section->getError());
			}
		}else{
			$this->assign('jumpUrl',__URL__.'/index');
			$this->error('添加失败'.$section->getError());
		}
	}
	
	/**
	 * 单元  编辑页面
	 */
	function edit(){
		//得到Id
		$sec_id = $_GET['id'];
		$section = new SectionModel();
		//动态获取数据
		$list = $section->getById($sec_id);
		$this->assign('slist',$list);
		//显示页面
		$this->display();
	}
	
	/**
	 * 单元 编辑后更新数据库
	 */
	function update(){
		$section = new SectionModel();
		if(!!$data = $section->create()){
			if(!empty($data['id'])){
				if(false !== $section->save()){
					//同时更新menu_item里面的标题
					$menu_item = M('MenuItem');
					$title = $data['title'];
					$new_data = array(
							'title'	=>	$title,	
						);
					$new_where = array(
							'type_id'	=>	array('eq',$data['id']),
							'type'		=>	'Section',
						);
					$bools = $menu_item->where($new_where)->save($new_data);
					if($bools){
						$this->assign('jumpUrl',__URL__.'/index');
						$this->success('更新成功');
					}
				}else{
					$this->error('更新失败：'.$section->getDbError());
				}
			}else{
				$this->error('请选择编辑用户');
			}
		}else{
			$this->error('更新失败：( '.$section->getError().' )');
		}
	}
	
	/**
	 * 单元删除页面
	 */
	function del(){
		//因为删除一个单元，就要删除其手下的分类类别，而删除分类类别后就要删除该类别下面的文章
		//所有必须使用关联操作来执行
		//序列化主键Id为：1,2,3,...以便批量删除
		$del_id = $_POST['del_id'];
		$section_id = implode($del_id, ',');
		//实例化
		$section	= 	new SectionModel();
		$category 	= 	new CategoryModel();
		$article	=	new ArticleModel();
		$menu_items = 	new MenuItemModel();
		
		//如果单元删除成功
		if( $section->delete($section_id) ){
			//删除下属分类以及分类下属文章
			$where = array(
				'sectionid'	=>	array('in',$section_id)
			);
			//删除分类
			$category->where($where)->delete();
			//删除文章
			$article->where($where)->delete();
			
			//删除单元后删除菜单项中的单元
			$menu_where = array(
				'type_id'	=>	array('in',$section_id),
				'type'		=>	'Section'
			);
			$menu_items->where($menu_where)->delete();
			
			
			$this->assign('jumpUrl',__URL__.'/index');
			$this->success('单元,菜单项以及所有相关文章类别已删除~~~~');
		}else{
			$this->assign('jumpUrl',__URL__.'/index');
			$this->error('删除失败~~~~'.$category->getError());
		}
	}
	
	/**
	 * 查看单元下属分类
	 */
	function look(){
		import('ORG.Util.Page');
		$categorys = new CategoryViewModel();
		$sec_id = $_GET['id'];	
		
		$where = array(
			'sectionid'	=>	array('eq',$sec_id),
		);	
				
		$count = $categorys->where($where)->count();		
		$page = new Page($count,C('PAGESIZE'));
		$show = $page->show();
		$list = $categorys->where($where)->limit($page->firstRow.','.$page->listRows)->select();	
		
		$this->assign("show",$show);
		$this->assign('cat_info',$list);
		
		$this->display();
	}
}
?>