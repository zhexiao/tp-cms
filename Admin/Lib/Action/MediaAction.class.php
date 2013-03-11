<?php
class MediaAction extends CommonAction{
	/**
	 * 媒体页面  首页
	 */
	function index(){
		import('ORG.Util.Page');
		$medias = new MediaModel();
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
			$where['description'] = array('like','%'.$keyword.'%');
		}
		
		//切换状态
		$change_pub = trim($_POST['change_published']);
		if($change_pub){
			$where['published'] = array('eq',$change_pub);
		}
		//传值在前台判断
		$this->assign('change_pub',$change_pub);
		
		//得到分页需要的总文章数
		$count = $medias->where($where)->count();
		$page = new Page($count,C('PAGESIZE'));
		$show = $page->show();
		$this->assign("show",$show);
		//得到文章数据
		$list = $medias->where($where)->order('id DESC')->limit($page->firstRow.','.$page->listRows)->select();	
		$this->assign('medias',$list);
		
		
		$this->display();
	}
	
	/**
	 * 创建视频 页面
	 */
	function add(){
		$this->display();
	}
	
	/**
	 * 查找给定的链接里面的 swf路径 并保存进数据库
	 */
	function search_media(){
		//得到Url 例如http://v.youku.com/v_show/id_XMzI3Nzc2ODY0.html
		$video_url = $_POST['video_url'];
		//获得 id_XMzI3Nzc2ODY0.
		$pattern = "/id_.*\./";
		preg_match($pattern, $video_url,$matchs);
		$info = $matchs[0];
		//得到 XMzI3Nzc2ODY0.
		$info_arr = explode('_', $info);
		//最后除去后面的.
		$swf = $info_arr[count($info_arr)-1];
		$length = strlen($swf);
		$swf = substr($swf,0,$length-1);
		//ajax输出
		$last_swf = 'http://player.youku.com/player.php/sid/'.$swf.'/v.swf';
		echo $last_swf;
	}
	
	/**
	 * 将数据插入数据库
	 */
	function add_db(){
		//由于表单令牌出错，故不用create方法
		$medias = new MediaModel();
		$video_url = trim($_POST['video_url']);
		$swf_url = trim($_POST['swf_url']);
		$description = trim($_POST['description']);
		$data['video_url'] = $video_url;
		$data['swf_url'] = $swf_url;
		$data['description'] = $description;
		$data['create_date'] = date("Y-m-d H:i:s",time());
		$data['published']  = 1;
		if ($medias->add($data)){
			$this->assign('jumpUrl',__URL__.'/index');
			$this->success('音频添加成功');
		}else{
			$this->assign('jumpUrl',__URL__.'/add');
			$this->error('音频添加失败，请联系管理员');
		}		
	}
	
	/**
	 * 更新 video 数据
	 */
	function edit_video(){
		$medias = new MediaModel();
		if( !!($data = $medias->create()) ){
			//如果有传ID过来
			if( !empty($data['id']) ){
				if( $medias->save() ){
					$this->assign('jumpUrl',__URL__.'/index');
					$this->success('音频更新成功');
				}else{
					$this->assign('jumpUrl',__URL__.'/index');
					$this->error('音频更新失败，请联系管理员'.$medias->getDbError());
				}
			}else{
				$this->assign('jumpUrl',__URL__.'/index');
				$this->error('请选择音频');
			}
		}else{
			$this->assign('jumpUrl',__URL__.'/index');
			$this->error('音频更新失败'.$medias->getError());
		}
	}
	
	/**
	 * 删除 video 数据
	 */
	function del(){
		//序列化主键Id为：1,2,3,...以便批量删除
		$del_id = $_POST['del_id'];
		$str = implode($del_id, ',');
		//实例化
		$medias = new MediaModel();
		//删除文章
		if($medias->delete($str)){
			$this->assign('jumpUrl',__URL__.'/index');
			$this->success('音频删除成功');
		}else{
			$this->assign('jumpUrl',__URL__.'/index');
			$this->error('音频删除失败');
		}
	}
	
	
}
?>