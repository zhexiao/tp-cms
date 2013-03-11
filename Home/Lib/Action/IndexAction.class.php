<?php
// 本文档自动生成，仅供测试运行
class IndexAction extends CommonAction{
    /**
    +----------------------------------------------------------
    * 首页
    +----------------------------------------------------------
    */
    public function index(){
    	//读取前台配置文章
    	$front_config = F('frontstage.inc','','./Config/');
    	//得到动态缓存时间
    	$dynamic_cache_time = $front_config['DYNAMIC_CACHE_TIME'];
    	
    	//实例化
    	$blocks = new BlockViewModel();
    	$artis	= new ArticleModel();  	 
    	//得到所有发布的菜单列表		
    	$where_list = array(
    		'm_i_published'	=>	array('eq',1),
    	);
		$data = $blocks->where($where_list)->order('m_i_id desc')->select();
		
		//动态缓存数据,如果没有则缓存,有则直接读取
		if( !S('list') ){
			$list = $data;
			S('list',$data,$dynamic_cache_time);
		}else{
			$list = S('list');
		}
		
		
		//循环所有的block区块里面的内容，得到相应的内容分配到不同的菜单数组中
		foreach ($list as $key=>$value) {
			switch ($value['blockname']){
				//头部导航
				case 'menu_top_nav':
					$menu_top_nav[$key]			=	$value;
					break;
				//幻灯片
				case 'menu_img_slide':
					$menu_img_slide[$key]		=	$value;	
					break;
				//热点置顶内容
				case 'menu_hot_content':
					$menu_hot_content[$key]		=	$value;
					break;
				//最新图文内容
				case 'menu_img_content':
					$menu_img_content[$key]		=	$value;
					break;
				//中间内容
				case 'menu_middle_content':
					$menu_middle_content[$key]	=	$value;
					break;
				//左侧内容
				case 'menu_side_bar':
					$menu_side_bar[$key]		=	$value;
					break;
			};
		}
		
		//因为热点内容区块需要一部分内容，所以得到description
    	foreach ($menu_hot_content as 	$key1=>$value1){
    		$where_hot	=	array(
    			'id'	=>	array('eq',$value1['m_i_type_id']),
    		);
			$description = $artis->field('description')->where($where_hot)->find();
			$menu_hot_content[$key1]['description'] = $description;
		}		
		//中间内容区块需要得到其下的文章列表
		foreach ($menu_middle_content as $key2=>$value2){
			$where_mid	=	array(
    			'catid'	=>	array('eq',$value2['m_i_type_id']),
    			'published'	=>	array('eq',1)
    		);
			$art_info	=	$artis->field('title,id')->order('id desc')->where($where_mid)->select();
			$menu_middle_content[$key2]['art_info']	=	$art_info;
		}
   		//左侧内容区块需要得到其下的文章列表
		foreach ($menu_side_bar as $key3=>$value3){
			$art_info	=	$artis->field('title,id')->order('id desc')->where('catid='.$value3['m_i_type_id'])->select();
			$menu_side_bar[$key3]['art_info']	=	$art_info;
		}
						
		$this->assign('menu_top_nav',$menu_top_nav);
		$this->assign('menu_img_slide',$menu_img_slide);
		$this->assign('menu_hot_content',$menu_hot_content);
		$this->assign('menu_img_content',$menu_img_content);
		$this->assign('menu_middle_content',$menu_middle_content);
		$this->assign('menu_side_bar',$menu_side_bar);
			
    	//调用head方法
		$this->head();	
		//得到友情链接
		$this->get_link();
	
      	$this->display();
    }
    
    /**
	 * 公用头部 方法
	 */	
	function head(){
		//读取前台配置文件,因为该文件没有包含进配置文件,使用快速方法 F
		$config = F('front_info.inc','','./Config/');
	
		$this->assign('config',$config);
		
		//获得滚动文章的列表
		$Articles = new ArticleModel();
		$art_where = array(
			"published"	=>	array('eq',1),
		);
		$list = $Articles->where($art_where)->order("id desc")->limit($config['rollnum'])->select();
		$this->assign('roll_arts',$list);
	}   	
	
	/**
	 * 得到友情链接
	 */
	function get_link(){
		$links = M('Link');
		$where = array(
			'published'	=>	array('eq',1),
		);
		$link_info = $links->where($where)->select();
		
		$this->assign('get_link',$link_info);
	}
}
?>