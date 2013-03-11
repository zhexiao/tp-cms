<?php
class ArticleAction extends CommonAction{
	/**
	 * 显示文章方法
	 */	
	function view(){
		//读取前台配置文章
		$front_config = F('frontstage.inc','','./Config/');
		//得到动态缓存时间
		$dynamic_cache_time = $front_config['DYNAMIC_CACHE_TIME'];
		
		$article = D('ArticleView');
		$adv_article = M('Article', 'AdvModel');
		$art_id = trim($_GET['id']);
		$where	=	array(
			'aid'	=>	array('eq',$art_id),
		);		
		$list = $article->where($where)->find();
		$cat_id = $list['cid'];
		
		//点击量+1
		$adv_article->setInc("hits","id=".$art_id,1);	
			
		//得到位置路径
		$sec_url = "<a href='".U('Section/view?id='.$list['sid'])."'>{$list['stitle']}</a>";
		$cat_url = "<a href='".U('Category/view?id='.$list['cid'])."'>{$list['ctitle']}</a>";
		$nav = ' > '.$sec_url.' > '.$cat_url.' > 正文';
		
		//得到上一篇文章和下一篇文章
		$where_up = array(
			'aid'	=>	array('lt',$art_id),
			'cid'	=>	array('eq',$cat_id),
		);
		$up_art = $article->where($where_up)->order('aid desc')->find();
		$where_next = array(
			'aid'	=>	array('gt',$art_id),
			'cid'	=>	array('eq',$cat_id),
		);
		$next_art = $article->where($where_next)->find();
		
		//得到该类下面的6篇文章作为相关文章
		$where_rela = array(
			'catid'	=>	array('eq',$cat_id),
		);
		$rela_cont = $article->where($where_rela)->limit('6')->select();
		
		//查找找数据库中文章为热门置顶的文章（art_attr=4）
		$where_hot_stick = array(
			'art_attr'	=>	array('eq',4),
		);
		$hot_stick_art_data = $article->where($where_hot_stick)->limit(10)->select();
		
		//查找数据库中点击量最高的10篇文章
		$click_hot_art_data = $article->limit(10)->order('hits desc')->select();
		
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
		
		$this->assign('nav',$nav);
		$this->assign('art_info',$list);
		$this->assign('up_art',$up_art);
		$this->assign('next_art',$next_art);
		$this->assign('rela_cont',$rela_cont);
		$this->assign('hot_stick_art',$hot_stick_art);
		$this->assign('click_hot_art',$click_hot_art);
		
		
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