<?php
class ArticleModel extends Model{
	//验证规则
	protected $_validate=array(
		array('title','require','标题必须填写!',1),
		array('sectionid','require','必须选择单元',1),
		array('catid','require','必须选择类别',1),
		array('description','require','内容必须填写',1),
	);
	
	//自动填充
	protected $_auto=array(
		array('created','getDate',1,'callback'),
		array('author','getUser',1,'callback'),
		array('modified','getDate',2,'callback'),
		array('modified_by','getUser',2,'callback'),
		array('publish_starttime','getStartTime','3','callback'),
		array('publish_endtime','getEndtTime','3','callback'),
		array('componentid','1')
	);
	
	//插入数据时得到当前时间
	function getDate(){
		return date('Y-m-d H:i:s');
	}
	//得到当前用户
	function getUser(){
		return $_SESSION['username'];
	}
	//得到发布的开始时间
	function getStartTime(){
		$published = $_POST['published'];
		if($published == 1){
			return date('Y-m-d H:i:s');
		}else{
			return '';
		}
	}
	//得到结束发布的时间
	function getEndtTime(){
		$published = $_POST['published'];
		if($published == 0){
			return date('Y-m-d H:i:s');
		}else{
			return '';
		}
	}
}
?>