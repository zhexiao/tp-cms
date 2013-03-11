<?php
class LanguageAction extends CommonAction{
	function index(){
		import("ORG.Io.Dir");
		//构造路径
		if( !$_GET['path'] && !$_GET['up'] && !$_SESSION['paht'] || $_GET['root'] ){
			//将根目录的值赋给SESSION[path]
			$_SESSION['path'] = './admin/Lang/';
		}
		//如果进入了文件夹，即点击了path,则寻找完整路径
		if( $_GET['path'] ){
			$_SESSION['path'] = $_SESSION['path'].'/'.$_GET['path'];
		}

		//得到目录路径
		$path = $_SESSION['path'];
		
		//返回上层目录
		if( $_GET['up'] ){
			//限制在网站根目录
			if( strlen($path) > strlen('./admin/Lang/') ){
				//得到上级路径存入match中
				preg_match('/^.*\//',$path,$match);
				$path = substr($match[0],0,-1);
				$_SESSION['path'] = $path;
				
			}else{
				$_SESSION['path'] = './admin/Lang/';
			}
		}

		//实例化
		$dir = new Dir($path);
		//注：这里需要把Dir.class.php中的private改为protected才能在外面调用
		$list = $dir->_values;
		
		foreach ($list as $key => $val){
			//得到不同类型的文件将图片属性存入进来
			$list[$key]['fileimg'] = $this->getFileImg($val);
			
			/*
			 * //因为目录的值不能直接得到,所以我们重新覆盖
			 * 由于过于影响速度。省了
			if( $val['type'] == 'dir' ){
				$list[$key]['size'] = calcuate_dir($val['pathname']);
			}
			*/
		}
				
		$this->assign('list',$list);	
		$this->display();
	}
	
	/**
	 * 得到文件图标类型的函数
	 */
	protected  function getFileImg($arr) {
		if( key_exists('type', $arr) ){
			//如果是目录
			if( $arr['type'] == 'dir' ){
				$filename = 'dir';
			//如果是文件
			}else if( $arr['type'] == 'file' ){
				switch ( $arr['ext'] ){
					case 'dir':
						$filename = 'dir';
						break;
					case 'php':
						$filename = 'php';
						break;
					case 'jpg':
						$filename = 'jpg';
						break;
					case 'gif':
						$filename = 'gif';
						break;
					case 'png':
						$filename = 'image';
						break;
					case 'js':
						$filename = 'js';
						break;
					case 'flash':
						$filename = 'flash';
						break;
					case 'css':
						$filename = 'css';
						break;
					case 'txt':
						$filename = 'txt';
						break;
					case 'zip':
						$filename = 'zip';
						break;
					case 'html':
						$filename = 'htm';
						break;
					case 'htm':
						$filename = 'htm';
						break;
					case 'wmv':
						$filename = 'wmv';
						break;
					case 'rm':
						$filename = 'rm';
						break;
					case 'mp3':
						$filename = 'mp3';
						break;						
					default:
						$filename = 'unknow';			
				}
			}
			$fileimg = '<img src="__PUBLIC__/Admin/images/file/'.$filename.'.gif" />';
			return $fileimg;
		}
	}
	
	/**
	 * 上传函数 
	 */
	function upload(){
		//导入上传类
		import("ORG.Net.UploadFile");
		if( !empty($_FILES['file']['name']) ){
			$upload = new UploadFile();
			//设置属性
			$upload->maxSize		=	3145728;
			$upload->allowExts		=	array('jpg', 'gif', 'png', 'jpeg','txt','php','css','js','html'); 
			$upload->savePath		=	$_SESSION['path'].'/';
			$upload->uploadReplace	=	true;
		  //$upload->saveRule		=	'uniqid';
		  	if( !$upload->upload() ){
		  		$this->assign('jumpUrl',__URL__.'/index');
		  		$this->error('上传失败,'.$upload->getError());
		  	}else{
		  		$this->assign('jumpUrl',__URL__.'/index');
		  		$this->success('上传成功');
		  	}
		}else{
			$this->assign('jumpUrl',__URL__.'/index');
		  	$this->error('请选择上传的文件,'.$upload->getError());
		} 	
	}
	
	/**
	 * 删除文件目录  函数
	 */
	function delete_file(){
		//导入文件类，调用删除函数
		import("ORG.Io.Dir");
		$dir = new Dir();
		//得到需要删除的目录或文件名
		$del_name = trim($_POST['del_name']);
		//得到文件或目录路径
		$path = $_SESSION['path'].'/'.$del_name;
		//判断是文件还是目录
		if( is_file($path) ){
			if( unlink($path) !== false){
				$this->assign('jumpUrl',__URL__.'/index');
		  		$this->success('文件删除成功');
			}else{
				$this->assign('jumpUrl',__URL__.'/index');
		  		$this->error('文件删除失败,请联系管理员');
			}
		}else if( is_dir($path) ){
			if( $dir->delDir($path) !== false){
				$this->assign('jumpUrl',__URL__.'/index');
		  		$this->success('目录删除成功');
			}else{
				$this->assign('jumpUrl',__URL__.'/index');
		  		$this->error('目录删除失败,请联系管理员');
			}
		}
	}
	
	/**
	 * 创建新的语言目录
	 */
	function new_folder(){
		$path = './admin/Lang/';
		if( $_POST['folder_name'] !== false){
			$pathname = $path.$_POST['folder_name'];
			if( mkdir($pathname) ){
				$this->assign('jumpUrl',__URL__.'/index');
		  		$this->success('目录创建成功');
			}else{
				$this->assign('jumpUrl',__URL__.'/index');
		  		$this->error('目录创建失败,请联系管理员');
			}
		}
		
	}
	
}
?>