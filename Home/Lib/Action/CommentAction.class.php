<?php
class CommentAction extends CommonAction{
	/**
	 * 插入数据库
	 */
	function insert(){
		$comment = D('Comment');
		$art_id = $_POST['art_id'];
		
		if( !!$data = $comment->create() ){
			
			if( $comment->add($data) !== false ){
				$this->assign('jumpUrl',__URL__.'/look/aid/'.$art_id);
				$this->success('评论成功');
			}else{
				$this->error('评论失败'.$comment->getDbError());
			}
		}else{
			$this->error('评论失败'.$comment->getError());
		}
	}
	
	/**
	 * 查看评论
	 */
	function look(){
		$art_id = trim($_GET['aid']);
		
		$comments = D("Comment");
		$article = D("ArticleView");
		
		$where = array(
			'art_id'	=>	array('eq',$art_id),
		);
		$list = $comments->order('id desc')->where($where)->select();
		
		$where_art = array(
			'aid'	=>	array('eq',$art_id),
		);
		$artinfo = $article->where($where_art)->find();
		
		//得到位置路径
		$sec_url = "<a href='".U('Section/view?id='.$artinfo['sid'])."'>{$artinfo['stitle']}</a>";
		$cat_url = "<a href='".U('Category/view?id='.$artinfo['cid'])."'>{$artinfo['ctitle']}</a>";
		$art_url = "<a href='".U('Article/view?id='.$art_id)."'>{$artinfo['atitle']}</a>";
		$nav = ' > '.$sec_url.' > '.$cat_url.' > '.$art_url.' > 评论';
		
		
		$this->assign('list',$list);
		$this->assign('nav',$nav);
		
		//调用文章头部
		$this->head();		
		$this->display();
	}
	
	/**
	 * 文章头部方法
	 * 显示文章公用头部
	 */
	function head(){
		$blocks = new BlockViewModel();
		//调用公用头部方法并得到其中的一些信息
		R('Index', 'head');
		
		$where_list = array(
    		'm_i_published'	=>	array('eq',1),
    	);
		$list = $blocks->where($where_list)->order('m_i_id desc')->select();
		foreach ($list as $key=>$value) {
			switch ($value['blockname']){
				//头部导航
				case 'menu_top_nav':
					$menu_top_nav[$key]			=	$value;
					break;
			}
		}
		//分配导航菜单
		$this->assign('menu_top_nav',$menu_top_nav);
	}
}
?>