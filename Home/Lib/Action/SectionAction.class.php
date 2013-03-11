<?php
class SectionAction extends CommonAction{
	/**
	 * 显示单元页面
	 */
	function view(){
		//读取前台配置文章
		$front_config = F('frontstage.inc','','./Config/');
		//得到动态缓存时间
		$dynamic_cache_time = $front_config['DYNAMIC_CACHE_TIME'];
		
		import('ORG.Util.Page');
		$articles = D('ArticleView');
		$sections = M('Section');
		
		$sec_id = trim($_GET['id']);
		$where = array(
			'sid'	=>	array('eq',$sec_id),
		);
		$count = $articles->where($where)->count();
		$page = new Page($count, C('PAGESIZE'));
		$show = $page->show();
		$list = $articles->where($where)->order('aid desc')->limit($page->firstRow.','.$page->listRows)->select();
		
		//得到位置路径
		$sec_url = "<a href='".U('Section/view?id='.$list[0]['sid'])."'>{$list[0]['stitle']}</a>";
		$nav = ' > '.$sec_url.' > 列表';
		
		//查找所有的分类
		$sec_list = $sections->field('title,id')->select();	

		//查找找数据库中文章为热门置顶的文章（art_attr=4）
		$where_hot_stick = array(
			'art_attr'	=>	array('eq',4),
		);
		$hot_stick_art_data = $articles->where($where_hot_stick)->limit(10)->select();
		
		//查找数据库中点击量最高的10篇文章
		$click_hot_art_data = $articles->limit(10)->order('hits desc')->select();
		
		//动态缓存热门置顶文章和点击量最高的10篇文章
		if( !S('hot_stick_art') ){
			$hot_stick_art = $hot_stick_art_data;
			S('hot_stick_art',$hot_stick_art_data,$dynamic_cache_time);
		}else{
			$hot_stick_art = S('hot_stick_art');
		}
		
		if( !S('click_hot_art') ){
			$click_hot_art = $click_hot_art_data;
			S('click_hot_art',$click_hot_art_data,$dynamic_cache_time);
		}else{
			$click_hot_art = S('click_hot_art');
		}
		
		$this->assign('show',$show);
		$this->assign('all_info',$list);
		$this->assign('nav',$nav);
		$this->assign('sec_list',$sec_list);
		$this->assign('hot_stick_art',$hot_stick_art);
		$this->assign('click_hot_art',$click_hot_art);
		
		//调用公用头部
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