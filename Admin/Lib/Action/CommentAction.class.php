<?php
class CommentAction extends CommonAction{
	/**
	 * 评论首页
	 */
	function index(){
		import('ORG.Util.Page');		
		//得到有评论的文章和评论数
		$articles = M('Article');
		$comments = M('Comment');
		
		//搜索项
		$keyword = $_POST['keyword'];
		if(is_numeric($keyword)){
			$where['art_id'] = array('eq',$keyword);
		}
		
		$artid_count = $comments->DISTINCT(true)->where($where)->field('art_id')->select();
		$count = count($artid_count);
		$page = new Page($count,C('PAGESIZE'));
		$show = $page->show();
		$this->assign("show",$show);
		
		//得到有评论的文章,DISTINCT去除相同值
		$artid_list = $comments->DISTINCT(true)->order('id desc')->where($where)->field('art_id')->limit($page->firstRow.','.$page->listRows)->select();
		foreach ( $artid_list as $key=>$val ){
			$com_art[$key]	= 	$articles->field('id,title,created')->getById($val['art_id']);
			$com_art[$key]['count']	=	$comments->where('art_id='.$val['art_id'])->count();
		}
		
		$this->assign('com_art',$com_art);
		$this->display();
	}
	
	/**
	 * 查看评论记录
	 */
	function look(){
		$aid = trim($_GET['aid']);
		$comments = D('CommentView');
		$where = array(
			'art_id'	=>	array('eq',$aid),
		);
		$list = $comments->where($where)->order('c_id desc')->select();
		
		$this->assign('list',$list);
		$this->display();
	}
	
	/**
	 * 删除全部评论
	 */
	function del(){
		$comments = M('Comment');
		//如果是删除全部,则是以POST表单传输数据
		if( !empty($_POST['del_id']) ){
			$del_id = $_POST['del_id'];
			$art_id = implode(',', $del_id);
			//删除全部直接删除文章ID对应的所有comment
			$where = array(
			'art_id'	=>	array('in',$art_id),
			);
		}else if( !empty($_GET['del_id']) ){
			//如果是删除单个，则是GET获取
			$del_id = $_GET['del_id'];
			//删除单个comment ID
			$where = array(
				'id'	=>	array('in',$del_id),
			);
		}
		
		if( $comments->where($where)->delete() !== false ){
			$this->assign('jumpUrl',U('Comment/index'));
			$this->success('评论删除成功');
		}else{
			$this->assign('jumpUrl',U('Comment/index'));
			$this->error('评论删除失败,请联系管理员');
		}
		
 	}
}
?>