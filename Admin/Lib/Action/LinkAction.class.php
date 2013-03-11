<?php
class LinkAction extends CommonAction{
	/**
	 * 显示 友情链接页面
	 */
	function index(){
		$links = D('Link');
		import('ORG.Util.Page');
		
		//搜索项
		$keyword = $_POST['keyword'];
		if(is_numeric($keyword)){
			$where['id'] = array('eq',$keyword);
		}else{
			$where['link_name'] = array('like','%'.$keyword.'%');
		}
		
		//切换文章状态
		$change_pub = trim($_POST['change_published']);
		if($change_pub){
			$where['published'] = array('eq',$change_pub);
		}
		//传值在前台判断
		$this->assign('change_pub',$change_pub);
		
		//得到分页需要的总数
		$count = $links->where($where)->count();
		$page = new Page($count,C('PAGESIZE'));
		$show = $page->show();
		$this->assign("show",$show);
		//得到数据
		$list = $links->where($where)->order('id desc')->limit($page->firstRow.','.$page->listRows)->select();
		
		$this->assign('list',$list);
		$this->display();
	}
	
	/**
	 * 添加 链接页面
	 */
	function add(){
		$this->display();
	}
	
	/**
	 * 插入 数据库
	 */
	function insert(){
		$links = D('Link');
		if( !!$data = $links->create() ){
			if( $links->add() !== false ){
				$this->assign('jumpUrl',U('Link/index'));
				$this->success('添加链接成功');
			}else{
				$this->error('添加链接失败'.$links->getDbError());
			}
		}else{
			$this->error('添加链接失败'.$links->getError());
		}
	}
	
	/**
	 * 编辑 链接页面
	 */
	function edit(){
		$link_id = $_GET['id'];
		$links = D('Link');

		$list = $links->getById($link_id);
		$this->assign('list',$list);
		$this->display();
	}
	
	/**
	 * 更新 链接页面
	 */
	function update(){
		$links = D('Link');
		if( !!$data = $links->create() ){
			if( !empty($data['id']) ){
				if( $links->save($data) !== false ){
					$this->assign('jumpUrl',U('Link/index'));
					$this->success('更新链接成功');
				}else{
					$this->error('更新链接失败'.$links->getDbError());
				}
			}else{
				$this->error('更新链接失败,请选择链接');
			}
		}else{
			$this->error('更新链接失败'.$links->getError());
		}
	}
	
	/**
	 * 删除 链接
	 */
	function del(){
		//序列化主键Id为：1,2,3,...以便批量删除
		$del_id = $_POST['del_id'];
		$str = implode($del_id, ',');
		//实例化
		$links = D('Link');
		//删除文章
		if($links->delete($str)){
			$this->assign('jumpUrl',__URL__.'/index');
			$this->success('链接删除成功');
		}else{
			$this->assign('jumpUrl',__URL__.'/index');
			$this->error('链接删除失败');
		}
	}
	
}
?>