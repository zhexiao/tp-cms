<?php
class CategoryAction extends CommonAction{
	/**
	 * 分类页面显示首页
	 */
	function index(){
		import('ORG.Util.Page');
		$categorys = new CategoryViewModel();
		//判断是否有搜索
		$keyword = trim($_POST['keyword']);
		//搜索项
		if(is_numeric($keyword)){
			$where['Category.id'] = array('eq',$keyword);
		}else{
			$where['Category.title'] = array('like','%'.$keyword.'%');
		}
		
		//切换状态
		$change_pub = trim($_POST['change_published']);
		if($change_pub){
			$where['Category.published'] = array('eq',$change_pub);
		}
		//传值在前台判断
		$this->assign('change_pub',$change_pub);
		
		//得到分页需要的总文章数
		$count = $categorys->where($where)->count();
		$page = new Page($count,C('PAGESIZE'));
		$show = $page->show();
		$this->assign("show",$show);
		//得到文章数据
		$list = $categorys->where($where)->order('Section.title,Category.id DESC')->limit($page->firstRow.','.$page->listRows)->select();	
		$this->assign('clist',$list);
		
		//分配配置中的每页显示多少文章到前台
		$this->assign('pagesize',C('PAGESIZE'));
		
		$this->display();
	}
	
	/**
	 * 添加分类页面
	 */
	function add(){
		//得到所有的section名称
		$sec = new SectionModel();
		$list = $sec->order('id')->select();
		$this->assign('slist',$list);
		
		$this->display();
	}
	
	/**
	 * 添加分类插入数据库函数
	 */
	function insert(){
		$category = new CategoryModel();
		//如果没有选择单元
		if($_POST['sectionid'] == -1){
			$this->assign('jumpUrl',__URL__.'/add');
			$this->error('请选择正确的单元');
		}else{
			//如果数据压入成功
			if(($data = $category->create()) !== false){
				if ($category->add()){
					$this->assign('jumpUrl',__URL__.'/index');
					$this->success('添加新类别成功');
				}else{
					$this->assign('jumpUrl',__URL__.'/index');
					$this->error('添加新类别失败'.$category->getDbError());
				}
			}else{
				$this->assign('jumpUrl',__URL__.'/index');
				$this->error('添加失败'.$category->getError());
			}
		}
	}
	
	/**
	 * 分类编辑页面
	 */
	function edit(){
		//得到Get传来的Id
		$cate_id = $_GET['id'];
		$category = new CategoryModel();
		//where语句
		$where = array(
			'id' => $cate_id
		);
		//获得分类表数据库数据
		$list = $category->where($where)->find();
		$this->assign('clist',$list);
		//获得单元表的所有单元
		$sec = new SectionModel();
		$list = $sec->select();
		$this->assign('slist',$list);
		
		$this->display();
	}
	
	/**
	 * 编辑后更新数据库数据
	 */
	function update(){
		$category = new CategoryModel();
		if(!!$data = $category->create()){
			if(!empty($data['id'])){
				if(false !== $category->save()){
					//同时更新menu_item里面的标题
					$menu_item = M('MenuItem');
					$title = $data['title'];
					$new_data = array(
							'title'	=>	$title,	
						);
					$new_where = array(
							'type_id'	=>	array('eq',$data['id']),
							'type'		=>	'Category',
						);
					$bools = $menu_item->where($new_where)->save($new_data);
					if($bools){
						$this->assign('jumpUrl',__URL__.'/index');
						$this->success('更新成功');
					}
				}else{
					$this->error('更新失败：'.$category->getDbError());
				}
			}else{
				$this->error('请选择编辑用户');
			}
		}else{
			$this->error('更新失败：( '.$category->getError().' )');
		}
	}
	
	/**
	 * 删除数据
	 */
	function del(){
		//implode序列化主键Id为：1,2,3,...以便批量删除
		$del_id = $_POST['del_id'];
		$catid = implode($del_id, ',');
		//实例化模型
		$category 	= new CategoryModel();
		$article	= new ArticleModel();
		$menu_items = new MenuItemModel();
		
		//删除分类
		if( $category->delete($catid) ){
			//删除文章的where语句
			$where = array(
				'catid'	=>	array('in',$catid)
			);
			//删除文章
			$article->where($where)->delete();
			
			//删除类别后删除菜单项中的类别
			$menu_where = array(
				'type_id'	=>	array('in',$catid),
				'type'		=>	'Category'
			);
			$menu_items->where($menu_where)->delete();
			
			$this->assign('jumpUrl',__URL__.'/index');
			$this->success('分类,下属文章以及相应的菜单项删除成功~~~~');
		}else{
			$this->assign('jumpUrl',__URL__.'/index');
			$this->error('分类删除失败!'.$category->getError());
		}
	}
	
	/**
	 * 查看分类下属文章
	 */
	function look(){
		import('ORG.Util.Page');
		$articles = new ArticleViewModel();
		$cat_id = $_GET['id'];	
		
		$where = array(
			'catid'	=>	array('eq',$cat_id),
		);	
				
		$count = $articles->where($where)->count();		
		$page = new Page($count,C('PAGESIZE'));
		$show = $page->show();
		$list = $articles->where($where)->limit($page->firstRow.','.$page->listRows)->select();	
		
		$this->assign("show",$show);
		$this->assign('art_info',$list);
	
		$this->display();
	}
	
}

?>