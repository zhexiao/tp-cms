<?php
class ManageAction extends CommonAction{
	
	/**
	 * 首页，调用3个frame页面
	 */
	function index(){
						
		//得到最新的7篇文章
		$arts = new ArticleModel();
		$list_arts_data = $arts->limit('7')->order('id desc')->select();
		//动态缓存最新文章
		if( !S('art_list') ){
			$art_list = $list_arts_data;
			S('art_list',$list_arts_data,3600);
		}else{
			$art_list = S('art_list');
		}
		$this->assign('art_list',$art_list);
		
		//得到最新的5次登陆记录
		$logs = new LoginRecordModel();
		$list_logs_data = $logs->limit('7')->order('id desc')->select();
		//动态缓存登陆记录
		if( !S('log_list') ){
			$log_list = $list_logs_data;
			S('log_list',$list_logs_data,3600);
		}else{
			$log_list = S('log_list');
		}
		$this->assign('log_list',$log_list);
		
		//得到最新的文章评论
		$comments = D('Comment');
		$list_com_data = $comments->limit('7')->order('id desc')->select();
		//动态缓存留言记录
		if( !S('com_list') ){
			$com_list = $list_com_data;
			S('com_list',$list_com_data,3600);
		}else{
			$com_list = S('com_list');
		}
		$this->assign('com_list',$com_list);
		
		$this->display();
	}
}
?>