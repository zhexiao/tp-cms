<?php
class CommentModel extends Model{
	protected $_validate = array(
		array('comment','require','请不要留空评论'),
	);
	
	protected $_auto = array(
		array('name','getName',1,'callback'),
		array('created','getDate',1,'callback'),
	);
	
	function getName(){
		if( empty($_POST['name']) ){
			return '匿名';
		}else{
			return $_POST['name'];
		}
	}
	
	function getDate(){
		return date('Y-m-d H:i:s',time());
	}
}