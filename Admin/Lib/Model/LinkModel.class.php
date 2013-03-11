<?php
class LinkModel extends Model{
	protected $_validate = array(
		array('link_name','require','标题必须填写!',1),
		array('link_url','require','链接必须填写',1),
	);	
	
	protected $_auto = array(
		array('created','getDate',1,'callback'),
	);
	
	//插入数据时得到当前时间
	function getDate(){
		return date('Y-m-d H:i:s');
	}
}