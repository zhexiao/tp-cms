<?php
class BlockAction extends CommonAction{
	/**
	 * 区块 首页显示
	 */
	function index(){
		//读取 布局文件
		$this->read_layout();
		
		$this->display();
		
	}
	
	/**
	 * 读取layout里面的配置
	 */
	function read_layout(){
		//转换为./Home/tpl/default/layout.php
		$layout_path = str_replace(__ROOT__.'/'.APP_NAME, './Home', APP_TMPL_PATH);

		//读取文件里面的内容	
		$layout = F('layout','',$layout_path);

		$this->assign('layouts',$layout);
	}
	
	/**
	 * 区块添加菜单
	 */
	function add(){
		//得到名字
		$block_name = $_GET['blockname'];	
		$this->assign('block_name',$block_name);

		//缓存名字
		$_SESSION['block_name'] = $block_name;
	
		
		//查询出所有已启用的菜单项
		$menu = new MenuModel();
		$list = $menu->where('published=1')->select();
		$this->assign("menus",$list);
	

		$this->display();
	}
	
	/**
	 * 显示添加 界面
	 */
	function addin(){
		//得到所选菜单的id 
		$menu_id = $_POST['sel_menu_id'];
		$memu_title = $_POST['memu_title'];
		//得到该菜单下面的所有单元，调用视图模型
		$menu = new MenuViewModel();
		$where	=	array(
			'm_id'	=>	$menu_id,
		);
		$list = $menu->where($where)->select();
		
		$this->assign("menuitems",$list);
		$this->assign("memu_title",$memu_title);
		
		$this->display();
	}
	
	/**
	 * 插入数据库
	 */
	function insert(){
		$blocks = new BlockModel();
		if( !!($data = $blocks->create()) ){	
			if( ($blocks->add($data)) !== false ){
				$this->assign('jumpUrl',__URL__.'/index');
				$this->success('添加成功');
			}else{
				$this->error('插入数据库失败'.$blocks->getDbError());
			}
		}else{
			$this->error('插入数据库失败'.$blocks->getDbError());
		}
	}
	
	/**
	 * 查看区块中菜单
	 */
	function look(){
		//得到区块名字
		$block_name = $_GET['blockname'];
		
		$blocks = new BlockViewModel();
		$where = array(
			'blockname'	=>	array('eq',$block_name),
		);
		$list = $blocks->where($where)->select();
		
		
		$this->assign('block_desc',$block_desc);
		$this->assign('block_name',$block_name);
		$this->assign('menuitems',$list);
		$this->display();
	}
	
	/**
	 * 删除区块 菜单
	 */
	function del(){
			//得到区块名字
		$block_name = $_GET['blockname'];
		
		$blocks = new BlockViewModel();
		$where = array(
			'blockname'	=>	array('eq',$block_name),
		);
		$list = $blocks->where($where)->select();
		$block_desc = $list[0]['blockdesc'];
		
		$this->assign('block_desc',$block_desc);
		$this->assign('block_name',$block_name);
		$this->assign('menuitems',$list);
		$this->display();
	}
	
	/**
	 * 完成删除
	 */
	function delin(){
		$block_name = $_POST['blockname'];
		$blocks = new BlockModel();
	
		$where = array(
			'blockname'	=>	array('eq',$block_name),
		);
		if( $blocks->where($where)->delete() !== false ){
			$this->assign('jumpUrl',__URL__.'/index');
			$this->success('删除成功');
		}else{
			$this->assign('jumpUrl',__URL__.'/index');
			$this->error('删除失败');
		}
	}
	
}
?>