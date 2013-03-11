<?php
class ArticleAction extends CommonAction{
	/**
	 * 文章显示页面
	 */
	function index(){
		import('ORG.Util.Page');
		$articles = new ArticleViewModel();
		
		//搜索项
		$keyword = $_POST['keyword'];
		if(is_numeric($keyword)){
			$where['Article.id'] = array('eq',$keyword);
		}else{
			$where['Article.title'] = array('like','%'.$keyword.'%');
		}
		
		
		
		//切换文章状态
		$change_pub = trim($_POST['change_published']);
		if($change_pub){
			$where['Article.published'] = array('eq',$change_pub);
		}
		//传值在前台判断
		$this->assign('change_pub',$change_pub);
		
		//得到分页需要的总文章数
		$count = $articles->where($where)->count();
		$page = new Page($count,C('PAGESIZE'));
		$show = $page->show();
		$this->assign("show",$show);
		//得到文章数据
		$list = $articles->order('Article.id DESC')->where($where)->limit($page->firstRow.','.$page->listRows)->select();	
	
		$this->assign('alist',$list);
		//分配配置中的每页显示多少文章到前台
		$this->assign('pagesize',C('PAGESIZE'));
		
		$this->display();
		
	}
	
	/**
	 * 文章添加页面
	 */
	function add(){
		//得到所有的section名称
		$sec = new SectionModel();
		$list = $sec->order('id')->select();
		$this->assign('slist',$list);
		//得到所有的category名称
		$cat = new CategoryModel();
		$list = $cat->order('sectionid,id')->select();
		$this->assign('clist',$list);
		
		$this->display();
	}
	
	/**
	 * 文章添加插入数据库
	 */
	function insert(){
		$article = new ArticleModel();
		if(!!$data = $article->create()){
			if(false !== $article->add()){
				$this->assign('jumpUrl',__URL__.'/index');
				$this->success('添加文章成功');
			}else{
				$this->error('添加失败'.$article->getDbError());
			}
		}else{
			$this->error('操作失败：数据验证( '.$article->getError().' )');
		}		
	}
	
	/**
	 * 文章编辑页面
	 */
	function edit(){
		$art_id = $_GET['id'];
		if(!empty($art_id)){
			//获得文章
			$art=new ArticleModel();
			$list=$art->getById($art_id);
			$this->assign('alist',$list);
			
			//获得单元
			$sec=new SectionModel();
			$list=$sec->order('id')->select();
			$this->assign('slist',$list);
			//获得分类
			$cat=new CategoryModel();
			$list=$cat->select();
			$this->assign('clist',$list);
		}
		
		$this->display();
	}
	
	/**
	 * 文章编辑更新页面
	 */
	function update(){
		$article = new ArticleModel();
		if(!!$data = $article->create()){
			if(!empty($data['id'])){
				if(false!==$article->save()){
					$this->assign('jumpUrl',__URL__.'/index');
					$this->success('更新成功');
				}else{
					$this->error('更新失败：'.$article->getDbError());
				}
			}else{
				$this->error('请选择编辑用户');
			}
		}else{
			$this->error('更新失败：( '.$article->getError().' )');
		}
	}
	
	/**
	 * 删除文章页面
	 */
	function del(){
		//序列化主键Id为：1,2,3,...以便批量删除
		$del_id = $_POST['del_id'];
		$str = implode($del_id, ',');
		//实例化
		$article = new ArticleModel();
		//删除了文章的同时删除掉菜单中的文章,其中type_id为外键
		$menu_items = new MenuItemModel();
		
		//删除文章
		if($article->delete($str)){
			//删除文章后删除菜单项中的文章
			$where = array(
				'type_id'	=>	array('in',$str),
				'type'		=>	'Article'
			);
			$menu_items->where($where)->delete();
			
			$this->assign('jumpUrl',__URL__.'/index');
			$this->success('文章以及相应菜单中文章已删除成功');
		}else{
			$this->assign('jumpUrl',__URL__.'/index');
			$this->error('文章删除失败');
		}
	}
}
?>